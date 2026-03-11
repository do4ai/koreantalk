(function () {
    const pageMode = document.body.dataset.viewerMode || "auto";
    const apiBaseMeta = document.querySelector('meta[name="kt-api-base"]');
    const apiBase = apiBaseMeta ? apiBaseMeta.content.trim() : "";
    const pdfWorkerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

    pdfjsLib.GlobalWorkerOptions.workerSrc = pdfWorkerSrc;

    const state = {
        sessionToken: null,
        manifest: null,
        mode: pageMode,
        pdfDoc: null,
        currentPage: 1,
        scale: 1,
        loading: false,
        speakingAborter: null,
        currentPhase: null,
        progressTimer: null,
        examAnswers: {},
        submittedPages: {},
        grammarLookup: {},
    };

    const elements = {
        title: document.getElementById("viewerTitle"),
        subtitle: document.getElementById("viewerSubtitle"),
        modeChip: document.getElementById("viewerModeChip"),
        securityChip: document.getElementById("viewerSecurityChip"),
        expiresText: document.getElementById("viewerExpiresText"),
        pageText: document.getElementById("viewerPageText"),
        authorText: document.getElementById("viewerAuthorText"),
        stageTag: document.getElementById("stageTag"),
        stageStatus: document.getElementById("stageStatus"),
        canvas: document.getElementById("pdfCanvas"),
        canvasFrame: document.getElementById("canvasFrame"),
        canvasEmpty: document.getElementById("canvasEmpty"),
        overlay: document.getElementById("canvasOverlay"),
        overlayTitle: document.getElementById("overlayTitle"),
        overlayMessage: document.getElementById("overlayMessage"),
        sidebar: document.getElementById("sidebar"),
        progressLabel: document.getElementById("progressLabel"),
        progressFill: document.getElementById("progressFill"),
        progressValue: document.getElementById("progressValue"),
        secureHint: document.getElementById("secureHint"),
        controlsPrev: document.getElementById("controlPrev"),
        controlsNext: document.getElementById("controlNext"),
        controlsZoomIn: document.getElementById("controlZoomIn"),
        controlsZoomOut: document.getElementById("controlZoomOut"),
        controlsFit: document.getElementById("controlFit"),
        controlsFullscreen: document.getElementById("controlFullscreen"),
        controlsPrimary: document.getElementById("primaryActionBtn"),
        controlsSecondary: document.getElementById("secondaryActionBtn"),
        controlsClose: document.getElementById("closeViewerBtn"),
        sidebarSummary: document.getElementById("sidebarSummary"),
        sidebarBody: document.getElementById("sidebarBody"),
        toast: document.getElementById("viewerToast"),
    };

    const canvasContext = elements.canvas.getContext("2d");

    const MODE_COPY = {
        speaking: {
            label: "말하기 훈련",
            status: "준비시간과 말하기 시간을 기준으로 학습을 진행합니다.",
            actionPrimary: "말하기 시작",
            actionSecondary: "음성 중지",
        },
        exam: {
            label: "문제 풀이",
            status: "현재 페이지 문제를 확인하고 바로 답안을 선택할 수 있습니다.",
            actionPrimary: "이 페이지 제출",
            actionSecondary: "제출 초기화",
        },
        grammar: {
            label: "문법·어휘 학습",
            status: "페이지 요약과 핵심 어휘를 함께 보며 학습합니다.",
            actionPrimary: "핵심 요약 보기",
            actionSecondary: "사이드 패널 초기화",
        },
        auto: {
            label: "보안 뷰어",
            status: "구매한 회원 전용으로 발급된 세션만 허용됩니다.",
            actionPrimary: "새로고침",
            actionSecondary: "안내 보기",
        },
    };

    init().catch((error) => {
        console.error(error);
        showOverlay("접속을 완료하지 못했습니다", error.message || "뷰어 세션을 확인하는 중 오류가 발생했습니다.");
        toast("error", "뷰어 초기화에 실패했습니다.");
    });

    async function init() {
        bindEvents();

        const sessionToken = getSessionToken();
        if (!sessionToken) {
            renderBlockedState(
                "잘못된 접근입니다",
                "전자책 상세 화면에서 발급된 전용 버튼을 통해 다시 접속해 주세요. 원본 PDF URL 또는 직접 링크 공유 방식은 허용되지 않습니다."
            );
            return;
        }

        state.sessionToken = sessionToken;
        showOverlay("세션을 확인하고 있습니다", "구매 권한과 접속 가능한 전자책 범위를 검증하고 있습니다.");

        const manifest = await fetchManifest(sessionToken);
        state.manifest = normalizeManifest(manifest);
        state.mode = state.manifest.mode;
        loadStoredState();
        applyManifestToChrome();

        if (!state.manifest.pdf.streamUrl) {
            throw new Error("PDF 스트림 주소가 세션 응답에 포함되지 않았습니다.");
        }

        await loadPdf(state.manifest.pdf.streamUrl);

        if (state.currentPage > state.pdfDoc.numPages) {
            state.currentPage = 1;
        }

        await fitToWidth();
        hideOverlay();
        toast("success", "보안 세션이 확인되었습니다.");
    }

    function bindEvents() {
        elements.controlsPrev.addEventListener("click", () => changePage(-1));
        elements.controlsNext.addEventListener("click", () => changePage(1));
        elements.controlsZoomIn.addEventListener("click", () => zoom(0.12));
        elements.controlsZoomOut.addEventListener("click", () => zoom(-0.12));
        elements.controlsFit.addEventListener("click", fitToWidth);
        elements.controlsFullscreen.addEventListener("click", toggleFullscreen);
        elements.controlsPrimary.addEventListener("click", runPrimaryAction);
        elements.controlsSecondary.addEventListener("click", runSecondaryAction);
        elements.controlsClose.addEventListener("click", closeViewer);
        window.addEventListener("keydown", handleKeydown);
        window.addEventListener("resize", handleResize);
        document.addEventListener("fullscreenchange", updateFullscreenButton);
    }

    function getSessionToken() {
        const params = new URLSearchParams(window.location.search);
        return params.get("session") || params.get("token") || params.get("viewer_session") || "";
    }

    async function fetchManifest(sessionToken) {
        const endpoint = resolveApiUrl(`/api/viewer/session/${encodeURIComponent(sessionToken)}`);
        const response = await fetch(endpoint, {
            method: "GET",
            credentials: "include",
            headers: {
                Accept: "application/json",
            },
        });

        if (response.status === 401 || response.status === 403) {
            throw new Error("구매 권한이 확인되지 않았습니다. 로그인 상태와 전자책 구매 여부를 다시 확인해 주세요.");
        }

        if (!response.ok) {
            throw new Error(`세션 조회에 실패했습니다. (${response.status})`);
        }

        return response.json();
    }

    function resolveApiUrl(pathname) {
        if (!apiBase) {
            return pathname;
        }

        if (/^https?:\/\//.test(apiBase)) {
            return new URL(pathname, `${apiBase.replace(/\/$/, "")}/`).toString();
        }

        return `${apiBase.replace(/\/$/, "")}${pathname}`;
    }

    function normalizeManifest(raw) {
        const mode = normalizeMode(
            raw.mode ||
            raw.viewer_mode ||
            raw.viewer?.mode ||
            raw.book?.mode ||
            raw.book?.viewer_mode ||
            pageMode
        );
        const book = raw.book || {};
        const pdf = raw.pdf || {};
        const access = raw.access || {};
        const navigation = raw.navigation || {};
        const speakingPages = normalizeSpeakingPages(raw.speaking?.pages || raw.page_settings || {});
        const examPages = normalizeExamPages(raw.exam?.pages || raw.exam?.questions || raw.questions || []);
        const grammarPages = normalizeGrammarPages(raw.grammar?.pages || raw.grammar || {});

        return {
            title: book.title || raw.title || "전자책 보안 뷰어",
            subtitle: book.subtitle || raw.subtitle || "구매한 회원만 접속 가능한 학습 화면입니다.",
            author: book.author || raw.author || "KoreanTalk",
            mode,
            expiresAt: raw.expires_at || raw.expired_at || access.expires_at || "",
            memberName: access.member_name || raw.member_name || "",
            allowDownload: Boolean(access.allow_download || pdf.allow_download),
            supportUrl: navigation.support_url || raw.support_url || "",
            closeUrl: navigation.close_url || navigation.detail_url || raw.close_url || raw.detail_url || "",
            detailUrl: navigation.detail_url || raw.detail_url || "",
            pdf: {
                streamUrl: pdf.stream_url || raw.pdf_stream_url || pdf.proxy_url || raw.pdf_proxy_url || pdf.signed_url || raw.signed_pdf_url || "",
                downloadUrl: pdf.download_url || raw.pdf_download_url || "",
                totalPages: Number(pdf.page_count || raw.page_count || 0),
            },
            speakingPages,
            examPages,
            grammarPages,
            examAnswerVisibility: raw.exam?.answer_visibility || "never",
            examSubmitCopy: raw.exam?.submit_copy || "이 페이지 답안을 제출했습니다.",
        };
    }

    function normalizeMode(value) {
        if (!value) {
            return "exam";
        }

        const normalized = String(value).toLowerCase();
        if (normalized === "listening" || normalized === "reading" || normalized === "listening-reading") {
            return "exam";
        }

        if (normalized === "vocabulary") {
            return "grammar";
        }

        if (MODE_COPY[normalized]) {
            return normalized;
        }

        return "exam";
    }

    function normalizeSpeakingPages(source) {
        const pageMap = {};

        if (Array.isArray(source)) {
            source.forEach((entry) => {
                if (!entry || !entry.page) {
                    return;
                }
                pageMap[String(entry.page)] = {
                    script: entry.script || entry.text || "",
                    prepSeconds: Number(entry.prep_seconds || entry.prepTime || 0),
                    speakSeconds: Number(entry.speak_seconds || entry.speakTime || 0),
                    note: entry.note || entry.noteText || "",
                };
            });
        } else {
            Object.keys(source).forEach((page) => {
                const entry = source[page] || {};
                pageMap[String(page)] = {
                    script: entry.script || entry.text || "",
                    prepSeconds: Number(entry.prep_seconds || entry.prepTime || 0),
                    speakSeconds: Number(entry.speak_seconds || entry.speakTime || 0),
                    note: entry.note || entry.noteText || "",
                };
            });
        }

        if (Object.keys(pageMap).length === 0 && typeof window.resultDataRaw !== "undefined" && Array.isArray(window.resultDataRaw)) {
            window.resultDataRaw.forEach((entry) => {
                if (!entry.page) {
                    return;
                }
                const text = String(entry.pdf_text || entry.text || "").trim();
                const times = parseTimes(text);
                const noteIndex = text.search(/(Note\s*\.?\s*|대\s*답\s*[:.]?)/i);
                pageMap[String(entry.page)] = {
                    script: noteIndex >= 0 ? text.slice(0, noteIndex).trim() : text,
                    prepSeconds: times.prep,
                    speakSeconds: times.speak || 5,
                    note: noteIndex >= 0 ? text.slice(noteIndex).trim() : "",
                };
            });
        }

        return pageMap;
    }

    function normalizeExamPages(source) {
        const pageMap = {};

        const pushQuestion = (page, question) => {
            const pageKey = String(page);
            if (!pageMap[pageKey]) {
                pageMap[pageKey] = [];
            }
            pageMap[pageKey].push({
                number: question.number || question.id || question.question_no || pageMap[pageKey].length + 1,
                prompt: question.prompt || question.question || question.title || "문항",
                choices: Array.isArray(question.choices) ? question.choices : [],
                answer: typeof question.answer === "number" ? question.answer : null,
                explanation: question.explanation || "",
            });
        };

        if (Array.isArray(source)) {
            source.forEach((question) => {
                if (!question || !question.page) {
                    return;
                }
                pushQuestion(question.page, question);
            });
        } else {
            Object.keys(source).forEach((page) => {
                const questions = source[page];
                if (Array.isArray(questions)) {
                    questions.forEach((question) => pushQuestion(page, question));
                }
            });
        }

        if (Object.keys(pageMap).length === 0 && typeof window.examDataRaw !== "undefined" && Array.isArray(window.examDataRaw)) {
            window.examDataRaw.forEach((question) => {
                if (!question.page || !Array.isArray(question.choices) || question.choices.length < 2) {
                    return;
                }
                const placeholder = ["①", "②", "③", "④"];
                const isPlaceholderOnly = question.choices.every((choice, index) => choice === placeholder[index] || String(choice).trim() === "");
                if (!isPlaceholderOnly) {
                    pushQuestion(question.page, question);
                }
            });
        }

        return pageMap;
    }

    function normalizeGrammarPages(source) {
        const pageMap = {};

        if (Array.isArray(source)) {
            source.forEach((entry) => {
                if (!entry || !entry.page) {
                    return;
                }
                pageMap[String(entry.page)] = {
                    summary: entry.summary || entry.text || "",
                    vocabulary: Array.isArray(entry.vocabulary) ? entry.vocabulary : [],
                    tips: Array.isArray(entry.tips) ? entry.tips : [],
                };
            });
        } else if (source && typeof source === "object") {
            Object.keys(source).forEach((page) => {
                const entry = source[page];
                if (!entry || Array.isArray(entry)) {
                    return;
                }
                pageMap[String(page)] = {
                    summary: entry.summary || entry.text || "",
                    vocabulary: Array.isArray(entry.vocabulary) ? entry.vocabulary : [],
                    tips: Array.isArray(entry.tips) ? entry.tips : [],
                };
            });
        }

        return pageMap;
    }

    async function loadPdf(streamUrl) {
        state.loading = true;
        const resolvedUrl = resolvePdfUrl(streamUrl);
        const response = await fetch(resolvedUrl, {
            credentials: resolvedUrl.startsWith("http") && !resolvedUrl.startsWith(window.location.origin) ? "omit" : "include",
        });

        if (!response.ok) {
            throw new Error(`PDF 스트림을 불러오지 못했습니다. (${response.status})`);
        }

        const arrayBuffer = await response.arrayBuffer();
        const loadingTask = pdfjsLib.getDocument({ data: new Uint8Array(arrayBuffer) });
        state.pdfDoc = await loadingTask.promise;
        state.loading = false;
    }

    function resolvePdfUrl(streamUrl) {
        if (/^https?:\/\//.test(streamUrl)) {
            return streamUrl;
        }
        return new URL(streamUrl, window.location.origin).toString();
    }

    async function renderPage(pageNumber) {
        if (!state.pdfDoc) {
            return;
        }

        if (pageNumber < 1 || pageNumber > state.pdfDoc.numPages) {
            return;
        }

        state.currentPage = pageNumber;
        stopSpeaking();

        const page = await state.pdfDoc.getPage(pageNumber);
        const viewport = page.getViewport({ scale: state.scale });

        elements.canvas.width = viewport.width;
        elements.canvas.height = viewport.height;

        showCanvasBusy("페이지를 렌더링하고 있습니다", `${pageNumber}페이지를 준비하는 중입니다.`);
        await page.render({ canvasContext, viewport }).promise;
        hideCanvasBusy();

        elements.canvasEmpty.style.display = "none";
        syncChrome();
        renderSidebar();
        saveStoredState();
    }

    function syncChrome() {
        const totalPages = state.pdfDoc ? state.pdfDoc.numPages : state.manifest.pdf.totalPages || 0;
        const pageLabel = `${state.currentPage} / ${totalPages || "?"}`;
        const pageStrong = document.getElementById("sidebarPageStrong");
        const pageCount = document.getElementById("sidebarPageCount");

        elements.pageText.textContent = pageLabel;
        if (pageStrong) {
            pageStrong.textContent = state.currentPage;
        }
        if (pageCount) {
            pageCount.textContent = totalPages || "?";
        }
        elements.stageTag.textContent = MODE_COPY[state.mode].label;
        elements.stageStatus.textContent = MODE_COPY[state.mode].status;
        elements.controlsPrimary.textContent = MODE_COPY[state.mode].actionPrimary;
        elements.controlsSecondary.textContent = MODE_COPY[state.mode].actionSecondary;
        elements.controlsPrev.disabled = state.currentPage <= 1;
        elements.controlsNext.disabled = !state.pdfDoc || state.currentPage >= state.pdfDoc.numPages;
    }

    function renderSidebar() {
        renderSummaryCard();

        if (state.mode === "speaking") {
            renderSpeakingSidebar();
            return;
        }

        if (state.mode === "grammar") {
            renderGrammarSidebar();
            return;
        }

        renderExamSidebar();
    }

    function renderSummaryCard() {
        const memberCopy = state.manifest.memberName ? `${state.manifest.memberName} 님 전용 세션` : "구매 회원 전용 세션";
        elements.sidebarSummary.innerHTML = `
            <div class="sidebar-eyebrow">Secure Session</div>
            <div class="page-indicator">
                <strong id="sidebarPageStrong">${state.currentPage}</strong>
                <span>page / <span id="sidebarPageCount">${state.pdfDoc ? state.pdfDoc.numPages : (state.manifest.pdf.totalPages || "?")}</span></span>
            </div>
            <div class="viewer-chip-row">
                <span class="viewer-chip">${memberCopy}</span>
                <span class="viewer-chip">${MODE_COPY[state.mode].label}</span>
            </div>
        `;
    }

    function renderSpeakingSidebar() {
        const pageData = state.manifest.speakingPages[String(state.currentPage)] || {};
        const prep = pageData.prepSeconds || 0;
        const speak = pageData.speakSeconds || 0;
        const note = pageData.note || "";
        const script = pageData.script || "";

        elements.progressLabel.textContent = state.currentPhase ? state.currentPhase : "학습 대기";
        if (!state.currentPhase) {
            updateProgress(0, "준비되면 말하기를 시작하세요.");
        }

        elements.sidebarBody.innerHTML = `
            <section class="sidebar-section">
                <div class="sidebar-eyebrow">Speaking Script</div>
                <h2>현재 페이지 안내</h2>
                <div class="summary-card">${escapeHtml(script || "이 페이지에는 서버에서 전달된 말하기 스크립트가 없습니다.")}</div>
            </section>
            <section class="sidebar-section">
                <div class="stat-grid">
                    <div class="stat-card">
                        <span>준비 시간</span>
                        <strong>${prep}초</strong>
                    </div>
                    <div class="stat-card">
                        <span>말하기 시간</span>
                        <strong>${speak || 0}초</strong>
                    </div>
                </div>
            </section>
            <section class="sidebar-section">
                <div class="sidebar-eyebrow">Note</div>
                <h3>답안 힌트</h3>
                ${note ? `<div class="note-card">${escapeHtml(note)}</div>` : '<div class="empty-state">힌트가 없는 페이지입니다. 본문과 타이머에 집중해 학습을 진행하세요.</div>'}
            </section>
        `;
    }

    function renderExamSidebar() {
        const pageQuestions = state.manifest.examPages[String(state.currentPage)] || [];
        const answerVisibility = state.manifest.examAnswerVisibility;
        const submitted = Boolean(state.submittedPages[String(state.currentPage)]);
        const pageAnswers = state.examAnswers[String(state.currentPage)] || {};
        const shouldReveal = answerVisibility === "always" || (answerVisibility === "after_submit" && submitted);

        const answeredCount = Object.keys(pageAnswers).length;
        updateProgress(pageQuestions.length ? (answeredCount / pageQuestions.length) * 100 : 0, `${answeredCount}/${pageQuestions.length} 문항 선택`);

        const body = pageQuestions.length
            ? `<div class="question-list">${pageQuestions.map((question) => renderQuestionCard(question, pageAnswers, shouldReveal)).join("")}</div>`
            : '<div class="empty-state">이 페이지에 연결된 문제 데이터가 없습니다. 운영에서는 서버 manifest에 문항 JSON을 포함해 주세요.</div>';

        elements.sidebarBody.innerHTML = `
            <section class="sidebar-section">
                <div class="sidebar-eyebrow">Page Questions</div>
                <h2>현재 페이지 문제</h2>
                <p>PDF 본문을 보면서 오른쪽에서 바로 답안을 선택할 수 있습니다. 답안은 브라우저 세션 동안만 유지됩니다.</p>
            </section>
            <section class="sidebar-section">
                <div class="stat-grid">
                    <div class="stat-card">
                        <span>선택 완료</span>
                        <strong>${answeredCount}/${pageQuestions.length}</strong>
                    </div>
                    <div class="stat-card">
                        <span>정답 공개</span>
                        <strong>${answerVisibility === "never" ? "비공개" : shouldReveal ? "표시 중" : "제출 후"}</strong>
                    </div>
                </div>
            </section>
            ${body}
        `;

        bindQuestionButtons();
    }

    function renderQuestionCard(question, pageAnswers, shouldReveal) {
        const selected = pageAnswers[String(question.number)];
        const choices = (question.choices || []).map((choice, index) => {
            const classes = ["choice-btn"];
            if (selected === index) {
                classes.push("selected");
            }
            if (shouldReveal && typeof question.answer === "number") {
                if (question.answer === index) {
                    classes.push("correct");
                } else if (selected === index && question.answer !== index) {
                    classes.push("incorrect");
                }
            }
            return `
                <button
                    type="button"
                    class="${classes.join(" ")}"
                    data-question-number="${escapeAttribute(question.number)}"
                    data-choice-index="${index}"
                >
                    <strong>${index + 1}.</strong> ${escapeHtml(choice)}
                </button>
            `;
        }).join("");

        let answerNote = "";
        if (shouldReveal && typeof question.answer === "number") {
            const isCorrect = selected === question.answer;
            const resultCopy = isCorrect ? "정답입니다." : `정답은 ${question.answer + 1}번입니다.`;
            const extra = question.explanation ? ` ${question.explanation}` : "";
            answerNote = `<div class="answer-note ${isCorrect ? "correct" : "incorrect"}">${escapeHtml(resultCopy + extra)}</div>`;
        }

        return `
            <article class="question-card">
                <div class="question-card-header">
                    <span class="question-badge">${escapeHtml(question.number)}</span>
                    <span class="question-status">${selected === undefined ? "미선택" : `${selected + 1}번 선택`}</span>
                </div>
                <div class="question-prompt">${escapeHtml(question.prompt)}</div>
                <div class="choice-grid">${choices}</div>
                ${answerNote}
            </article>
        `;
    }

    function bindQuestionButtons() {
        elements.sidebarBody.querySelectorAll("[data-question-number]").forEach((button) => {
            button.addEventListener("click", () => {
                const pageKey = String(state.currentPage);
                const questionNumber = button.getAttribute("data-question-number");
                const choiceIndex = Number(button.getAttribute("data-choice-index"));

                if (!state.examAnswers[pageKey]) {
                    state.examAnswers[pageKey] = {};
                }

                state.examAnswers[pageKey][questionNumber] = choiceIndex;
                saveStoredState();
                renderExamSidebar();
            });
        });
    }

    function renderGrammarSidebar() {
        const pageData = state.manifest.grammarPages[String(state.currentPage)] || {};
        const vocabulary = Array.isArray(pageData.vocabulary) ? pageData.vocabulary : [];
        const tips = Array.isArray(pageData.tips) ? pageData.tips : [];

        updateProgress(vocabulary.length ? 100 : 0, vocabulary.length ? `${vocabulary.length}개 어휘 카드 준비` : "서버에서 요약 데이터를 기다리는 중");

        const vocabHtml = vocabulary.length
            ? `<div class="token-list">${vocabulary.map(renderVocabularyCard).join("")}</div>`
            : '<div class="empty-state">이 페이지에 연결된 핵심 어휘가 없습니다.</div>';

        const tipsHtml = tips.length
            ? `<ul class="list-block">${tips.map((tip) => `<li>${escapeHtml(tip)}</li>`).join("")}</ul>`
            : '<div class="empty-state">문법 팁이 등록되지 않았습니다.</div>';

        elements.sidebarBody.innerHTML = `
            <section class="sidebar-section">
                <div class="sidebar-eyebrow">Summary</div>
                <h2>핵심 요약</h2>
                <div class="summary-card">${escapeHtml(pageData.summary || "이 페이지의 요약이 아직 제공되지 않았습니다.")}</div>
            </section>
            <section class="sidebar-section">
                <div class="sidebar-eyebrow">Vocabulary</div>
                <h3>핵심 어휘</h3>
                ${vocabHtml}
            </section>
            <section class="sidebar-section">
                <div class="sidebar-eyebrow">Study Tips</div>
                <h3>학습 포인트</h3>
                ${tipsHtml}
            </section>
        `;
    }

    function renderVocabularyCard(entry) {
        const term = entry.term || entry.word || entry.title || "어휘";
        const meaning = entry.meaning || entry.description || "";
        const example = entry.example || "";

        return `
            <div class="token-card">
                <strong>${escapeHtml(term)}</strong>
                <div>${escapeHtml(meaning)}</div>
                ${example ? `<div style="margin-top:8px; color:var(--muted); font-size:0.92rem;">${escapeHtml(example)}</div>` : ""}
            </div>
        `;
    }

    function applyManifestToChrome() {
        const expiresCopy = formatExpiresAt(state.manifest.expiresAt);
        elements.title.textContent = state.manifest.title;
        elements.subtitle.textContent = state.manifest.subtitle;
        elements.modeChip.textContent = MODE_COPY[state.mode].label;
        elements.securityChip.textContent = "구매·권한 검증 완료";
        elements.expiresText.textContent = expiresCopy;
        elements.authorText.textContent = state.manifest.author;
        elements.secureHint.textContent = "이 화면은 서버 세션을 확인한 뒤에만 열립니다. 원본 PDF 주소를 직접 전달하지 않는 구조를 전제로 합니다.";
    }

    function runPrimaryAction() {
        if (state.mode === "speaking") {
            startSpeakingFlow();
            return;
        }

        if (state.mode === "grammar") {
            toast("success", "현재 페이지의 핵심 요약과 어휘 카드가 오른쪽 패널에 표시됩니다.");
            return;
        }

        const pageKey = String(state.currentPage);
        state.submittedPages[pageKey] = true;
        saveStoredState();
        renderExamSidebar();
        toast("success", state.manifest.examSubmitCopy);
    }

    function runSecondaryAction() {
        if (state.mode === "speaking") {
            stopSpeaking();
            toast("success", "말하기 재생을 중지했습니다.");
            return;
        }

        if (state.mode === "grammar") {
            elements.canvasFrame.scrollTo({ top: 0, behavior: "smooth" });
            toast("success", "현재 페이지 기준으로 보조 패널을 다시 정리했습니다.");
            return;
        }

        const pageKey = String(state.currentPage);
        delete state.submittedPages[pageKey];
        delete state.examAnswers[pageKey];
        saveStoredState();
        renderExamSidebar();
        toast("success", "이 페이지 답안을 초기화했습니다.");
    }

    async function startSpeakingFlow() {
        const pageData = state.manifest.speakingPages[String(state.currentPage)] || {};
        const script = pageData.script || "";
        const prepSeconds = pageData.prepSeconds || 0;
        const speakSeconds = pageData.speakSeconds || 0;

        stopSpeaking();

        if (prepSeconds > 0) {
            state.currentPhase = "준비 시간";
            await runCountdown(prepSeconds, "말하기 준비 중");
        }

        if (script) {
            state.currentPhase = "안내 음성";
            updateProgress(0, "현재 페이지 스크립트를 낭독합니다.");
            await speakText(script);
        }

        if (speakSeconds > 0) {
            state.currentPhase = "말하기 시간";
            await runCountdown(speakSeconds, "실제 말하기 시간을 측정합니다.");
        }

        state.currentPhase = null;
        updateProgress(0, "한 페이지 학습이 완료되었습니다.");
        renderSpeakingSidebar();
    }

    function speakText(text) {
        return new Promise((resolve) => {
            if (!window.speechSynthesis || !text) {
                resolve();
                return;
            }

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = "ko-KR";
            utterance.rate = 0.95;
            utterance.onend = () => resolve();
            utterance.onerror = () => resolve();
            window.speechSynthesis.cancel();
            window.speechSynthesis.speak(utterance);
            state.speakingAborter = { cancel: () => window.speechSynthesis.cancel() };
        });
    }

    function runCountdown(totalSeconds, label) {
        return new Promise((resolve) => {
            const startedAt = Date.now();
            const totalMs = totalSeconds * 1000;

            state.speakingAborter = {
                cancel: () => {
                    window.clearInterval(state.progressTimer);
                    state.progressTimer = null;
                    resolve();
                },
            };

            const tick = () => {
                const elapsed = Date.now() - startedAt;
                const remaining = Math.max(totalMs - elapsed, 0);
                const percentage = totalMs ? ((totalMs - remaining) / totalMs) * 100 : 100;
                updateProgress(percentage, `${label} · ${Math.ceil(remaining / 1000)}초 남음`);
                if (remaining <= 0) {
                    window.clearInterval(state.progressTimer);
                    state.progressTimer = null;
                    resolve();
                }
            };

            tick();
            state.progressTimer = window.setInterval(tick, 200);
        });
    }

    function stopSpeaking() {
        if (window.speechSynthesis) {
            window.speechSynthesis.cancel();
        }
        if (state.speakingAborter && typeof state.speakingAborter.cancel === "function") {
            state.speakingAborter.cancel();
        }
        if (state.progressTimer) {
            window.clearInterval(state.progressTimer);
            state.progressTimer = null;
        }
        state.speakingAborter = null;
        state.currentPhase = null;
        updateProgress(0, "재생 대기");
    }

    function updateProgress(value, label) {
        elements.progressFill.style.width = `${Math.max(0, Math.min(100, value))}%`;
        elements.progressValue.textContent = label;
    }

    async function changePage(diff) {
        if (!state.pdfDoc) {
            return;
        }
        await renderPage(state.currentPage + diff);
    }

    async function zoom(diff) {
        if (!state.pdfDoc) {
            return;
        }
        state.scale = Math.min(2.4, Math.max(0.7, state.scale + diff));
        await renderPage(state.currentPage);
    }

    async function fitToWidth() {
        if (!state.pdfDoc) {
            return;
        }
        const page = await state.pdfDoc.getPage(state.currentPage || 1);
        const viewport = page.getViewport({ scale: 1 });
        const frameWidth = elements.canvasFrame.clientWidth - 52;
        state.scale = frameWidth > 0 ? Math.max(0.7, Math.min(2.2, frameWidth / viewport.width)) : 1;
        await renderPage(state.currentPage || 1);
    }

    async function handleResize() {
        if (!state.pdfDoc) {
            return;
        }
        await renderPage(state.currentPage);
    }

    function handleKeydown(event) {
        if (!state.pdfDoc) {
            return;
        }

        if (event.key === "ArrowLeft") {
            event.preventDefault();
            changePage(-1);
        }

        if (event.key === "ArrowRight") {
            event.preventDefault();
            changePage(1);
        }
    }

    async function toggleFullscreen() {
        if (!document.fullscreenElement) {
            await elements.canvasFrame.requestFullscreen();
        } else {
            await document.exitFullscreen();
        }
        updateFullscreenButton();
    }

    function updateFullscreenButton() {
        elements.controlsFullscreen.textContent = document.fullscreenElement ? "전체화면 종료" : "전체화면";
    }

    function closeViewer() {
        const target = state.manifest.closeUrl || state.manifest.detailUrl || document.referrer || "/";
        window.location.href = target;
    }

    function loadStoredState() {
        const saved = safeJsonParse(sessionStorage.getItem(storageKey()), {});
        state.currentPage = Number(saved.currentPage || 1);
        state.scale = Number(saved.scale || 1);
        state.examAnswers = saved.examAnswers || {};
        state.submittedPages = saved.submittedPages || {};
    }

    function saveStoredState() {
        sessionStorage.setItem(storageKey(), JSON.stringify({
            currentPage: state.currentPage,
            scale: state.scale,
            examAnswers: state.examAnswers,
            submittedPages: state.submittedPages,
        }));
    }

    function storageKey() {
        return `koreantalk_secure_viewer:${state.sessionToken}`;
    }

    function renderBlockedState(title, message) {
        elements.title.textContent = title;
        elements.subtitle.textContent = message;
        elements.modeChip.textContent = "접근 차단";
        elements.securityChip.textContent = "세션 없음";
        elements.expiresText.textContent = "확인 불가";
        elements.authorText.textContent = "KoreanTalk";
        elements.canvasEmpty.style.display = "flex";
        elements.canvas.style.display = "none";
        elements.sidebarBody.innerHTML = `<div class="empty-state">${escapeHtml(message)}</div>`;
        showOverlay(title, message);
    }

    function showOverlay(title, message) {
        elements.overlayTitle.textContent = title;
        elements.overlayMessage.textContent = message;
        elements.overlay.classList.add("visible");
    }

    function hideOverlay() {
        elements.overlay.classList.remove("visible");
    }

    function showCanvasBusy(title, message) {
        showOverlay(title, message);
    }

    function hideCanvasBusy() {
        hideOverlay();
    }

    function toast(type, message) {
        elements.toast.className = `toast visible ${type}`;
        elements.toast.textContent = message;
        window.clearTimeout(toast._timer);
        toast._timer = window.setTimeout(() => {
            elements.toast.className = "toast";
            elements.toast.textContent = "";
        }, 2600);
    }

    function parseTimes(text) {
        if (!text) {
            return { prep: 0, speak: 0 };
        }

        const prepMatch = text.match(/(\d+)\s*초\s*동안\s*준비/);
        const speakMatch = text.match(/(\d+)\s*초\s*동안\s*(말|이야기)/);

        return {
            prep: prepMatch ? Number(prepMatch[1]) : 0,
            speak: speakMatch ? Number(speakMatch[1]) : 0,
        };
    }

    function formatExpiresAt(value) {
        if (!value) {
            return "만료 시간 비공개";
        }

        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return `${date.getFullYear()}.${pad(date.getMonth() + 1)}.${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }

    function pad(number) {
        return String(number).padStart(2, "0");
    }

    function safeJsonParse(text, fallback) {
        try {
            return text ? JSON.parse(text) : fallback;
        } catch (error) {
            return fallback;
        }
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;");
    }

    function escapeAttribute(value) {
        return escapeHtml(value);
    }
})();
