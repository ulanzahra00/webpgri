const vscode = require('vscode');

let panel;
let terminal;
let promptBuffer = '';
let writeEmitter;
let typingSpeed = 14;

function activate(context) {
  const command = vscode.commands.registerCommand('aiTypingProgress.open', () => {
    openProgressPanel(context);
    openPromptTerminal();
  });

  context.subscriptions.push(command);
}

function openPromptTerminal() {
  if (terminal) {
    terminal.show();
    return;
  }

  writeEmitter = new vscode.EventEmitter();
  const pty = {
    onDidWrite: writeEmitter.event,
    open: () => {
      writeEmitter.fire('\x1b[36mAI Prompt Terminal\x1b[0m\r\n');
      writeEmitter.fire('Ketik prompt lalu tekan Enter. Perintah: /help, /clear, /speed 1-5, /stop.\r\n\r\n');
      writePrompt();
    },
    close: () => {
      terminal = undefined;
      promptBuffer = '';
    },
    handleInput: (data) => {
      for (const char of data) {
        if (char === '\r') {
          const prompt = promptBuffer.trim();
          writeEmitter.fire('\r\n');
          promptBuffer = '';

        if (prompt) {
            runTerminalCommand(prompt);
          }

          writePrompt();
        } else if (char === '\u0003') {
          stopTyping();
          writeEmitter.fire('^C\r\n');
          writePrompt();
        } else if (char === '\x7f') {
          if (promptBuffer.length > 0) {
            promptBuffer = promptBuffer.slice(0, -1);
            writeEmitter.fire('\b \b');
          }
        } else if (char >= ' ') {
          promptBuffer += char;
          writeEmitter.fire(char);
        }
      }
    }
  };

  terminal = vscode.window.createTerminal({
    name: 'AI Prompt',
    pty
  });
  terminal.show();
}

function writePrompt() {
  writeEmitter.fire('\x1b[32mAI>\x1b[0m ');
}

function runTerminalCommand(input) {
  const value = input.trim();

  if (value === '/help') {
    writeEmitter.fire('\x1b[90m/help       tampilkan bantuan\x1b[0m\r\n');
    writeEmitter.fire('\x1b[90m/clear      bersihkan panel kanan\x1b[0m\r\n');
    writeEmitter.fire('\x1b[90m/speed 1-5  atur kecepatan typing, 5 paling cepat\x1b[0m\r\n');
    writeEmitter.fire('\x1b[90m/stop       hentikan animasi aktif\x1b[0m\r\n');
    return;
  }

  if (value === '/clear') {
    panel?.webview.postMessage({ type: 'clear' });
    writeEmitter.fire('\x1b[90mPanel kanan dibersihkan.\x1b[0m\r\n');
    return;
  }

  if (value === '/stop') {
    stopTyping();
    writeEmitter.fire('\x1b[90mAnimasi typing dihentikan.\x1b[0m\r\n');
    return;
  }

  if (value.startsWith('/speed')) {
    const [, levelRaw] = value.split(/\s+/);
    const level = Number(levelRaw);
    if (!Number.isInteger(level) || level < 1 || level > 5) {
      writeEmitter.fire('\x1b[31mFormat: /speed 1-5\x1b[0m\r\n');
      return;
    }

    typingSpeed = Math.max(4, 24 - (level * 4));
    panel?.webview.postMessage({ type: 'setSpeed', speed: typingSpeed });
    writeEmitter.fire(`\x1b[90mKecepatan typing diatur ke level ${level}.\x1b[0m\r\n`);
    return;
  }

  runPrompt(value);
}

function stopTyping() {
  panel?.webview.postMessage({ type: 'stop' });
}

async function runPrompt(prompt) {
  openProgressPanel();
  writeEmitter.fire(`\x1b[90mMengirim prompt ke panel kanan: ${prompt}\x1b[0m\r\n`);

  const editor = vscode.window.activeTextEditor;
  const fileName = editor ? vscode.workspace.asRelativePath(editor.document.uri) : 'preview.js';
  const source = editor ? getEditorSnippet(editor) : '';
  const generatedCode = buildAnimatedCode(prompt, fileName, source);

  panel.webview.postMessage({
    type: 'runPrompt',
    prompt,
    fileName,
    code: generatedCode,
    speed: typingSpeed
  });
}

function openProgressPanel() {
  if (panel) {
    panel.reveal(vscode.ViewColumn.Beside);
    return;
  }

  panel = vscode.window.createWebviewPanel(
    'aiTypingProgress',
    'AI Typing Progress',
    vscode.ViewColumn.Beside,
    {
      enableScripts: true,
      retainContextWhenHidden: true
    }
  );

  panel.webview.html = getWebviewHtml();
  panel.onDidDispose(() => {
    panel = undefined;
  });
}

function getEditorSnippet(editor) {
  const selection = editor.selection;
  const selected = editor.document.getText(selection);
  const text = selected || editor.document.getText();
  const lines = text.split(/\r?\n/);
  return lines.slice(0, 80).join('\n');
}

function buildAnimatedCode(prompt, fileName, source) {
  const escapedPrompt = prompt.replace(/\*\//g, '* /');
  const ext = fileName.split('.').pop().toLowerCase();

  if (ext === 'php') {
    return [
      '<?php',
      `// Prompt: ${escapedPrompt}`,
      '// Status: membaca file aktif dan menyiapkan perubahan...',
      '// Mode: live coding preview',
      '',
      'function preview_ai_change(array $context): array',
      '{',
      "    $steps = [];",
      "    $steps[] = 'Menganalisis struktur kode';",
      "    $steps[] = 'Menentukan bagian yang perlu diubah';",
      "    $steps[] = 'Menyusun patch perubahan';",
      '',
      '    return [',
      "        'file' => '" + fileName.replace(/\\/g, '\\\\').replace(/'/g, "\\'") + "',",
      "        'prompt' => '" + escapedPrompt.replace(/\\/g, '\\\\').replace(/'/g, "\\'") + "',",
      "        'steps' => $steps,",
      '    ];',
      '}',
      '',
      '// Preview cuplikan file aktif:',
      ...commentSource(source, '// ')
    ].join('\n');
  }

  return [
    `// Prompt: ${escapedPrompt}`,
    '// Status: membaca file aktif dan menyiapkan perubahan...',
    '// Mode: live coding preview',
    '',
    'const changePlan = {',
    `  file: ${JSON.stringify(fileName)},`,
    `  prompt: ${JSON.stringify(prompt)},`,
    '  steps: [',
    "    'Menganalisis struktur kode',",
    "    'Menentukan bagian yang perlu diubah',",
    "    'Menyusun patch perubahan',",
    "    'Menampilkan progres seperti programmer sedang mengetik'",
    '  ]',
    '};',
    '',
    'async function previewCodeProgress() {',
    '  for (const step of changePlan.steps) {',
    "    console.log('[AI]', step);",
    '  }',
    '}',
    '',
    '// Preview cuplikan file aktif:',
    ...commentSource(source, '// ')
  ].join('\n');
}

function commentSource(source, prefix) {
  if (!source) {
    return [`${prefix}Belum ada editor aktif. Buka file lalu kirim prompt lagi.`];
  }

  return source.split(/\r?\n/).slice(0, 30).map((line) => `${prefix}${line}`);
}

function getWebviewHtml() {
  const nonce = Date.now().toString();

  return `<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AI Typing Progress</title>
  <style>
    :root {
      color-scheme: dark;
      --bg: #0d1117;
      --panel: #111827;
      --line: #243244;
      --text: #e5edf7;
      --muted: #91a4b8;
      --green: #39d98a;
      --cyan: #5cc8ff;
      --yellow: #ffd166;
      --gutter: #6b7d90;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      background: var(--bg);
      color: var(--text);
      font: 13px/1.5 var(--vscode-editor-font-family, Consolas, monospace);
    }

    .shell {
      min-height: 100vh;
      display: grid;
      grid-template-rows: auto auto 1fr;
    }

    header {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      align-items: center;
      padding: 12px 14px;
      border-bottom: 1px solid var(--line);
      background: #0f1722;
    }

    h1 {
      margin: 0;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0;
    }

    .badge {
      color: var(--green);
      white-space: nowrap;
    }

    .meta {
      display: grid;
      gap: 4px;
      padding: 12px 14px;
      border-bottom: 1px solid var(--line);
      color: var(--muted);
    }

    .meta strong {
      color: var(--cyan);
      font-weight: 600;
    }

    .workspace {
      display: grid;
      grid-template-rows: auto 1fr;
      min-height: 0;
    }

    .status {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      padding: 10px 14px;
      border-bottom: 1px solid var(--line);
      background: var(--panel);
    }

    .step {
      border: 1px solid var(--line);
      border-radius: 6px;
      padding: 4px 8px;
      color: var(--muted);
    }

    .step.active {
      color: var(--yellow);
      border-color: var(--yellow);
    }

    .step.done {
      color: var(--green);
      border-color: rgba(57, 217, 138, 0.55);
    }

    .editor {
      display: grid;
      grid-template-columns: auto 1fr;
      min-height: 0;
      overflow: auto;
      background:
        linear-gradient(rgba(255, 255, 255, 0.03) 50%, transparent 50%) 0 0 / 100% 48px,
        var(--bg);
    }

    .gutter,
    pre {
      margin: 0;
      padding: 16px;
      min-height: 100%;
      white-space: pre-wrap;
    }

    .gutter {
      user-select: none;
      text-align: right;
      color: var(--gutter);
      border-right: 1px solid var(--line);
      background: #0a0f16;
    }

    pre {
      overflow: visible;
      word-break: break-word;
    }

    code {
      font: inherit;
    }

    .cursor {
      display: inline-block;
      width: 7px;
      height: 1.1em;
      margin-left: 2px;
      vertical-align: -2px;
      background: var(--green);
      animation: blink 0.8s steps(1) infinite;
    }

    @keyframes blink {
      50% { opacity: 0; }
    }
  </style>
</head>
<body>
  <main class="shell">
    <header>
      <h1>AI Typing Progress</h1>
      <span class="badge" id="badge">READY</span>
    </header>
    <section class="meta">
      <div>Prompt: <strong id="prompt">Ketik prompt di terminal "AI Prompt".</strong></div>
      <div>File: <strong id="file">-</strong></div>
    </section>
    <section class="workspace">
      <div class="status" id="status">
        <span class="step">Membaca prompt</span>
        <span class="step">Menganalisis file</span>
        <span class="step">Menulis kode</span>
        <span class="step">Selesai</span>
      </div>
      <div class="editor" id="editor">
        <pre class="gutter" id="gutter">1</pre>
        <pre><code id="code">// Panel siap menerima prompt dari terminal.</code><span class="cursor"></span></pre>
      </div>
    </section>
  </main>

  <script nonce="${nonce}">
    const editorEl = document.getElementById('editor');
    const gutterEl = document.getElementById('gutter');
    const codeEl = document.getElementById('code');
    const promptEl = document.getElementById('prompt');
    const fileEl = document.getElementById('file');
    const badgeEl = document.getElementById('badge');
    const steps = Array.from(document.querySelectorAll('.step'));
    let currentRun = 0;
    let typingDelay = 14;

    window.addEventListener('message', (event) => {
      const message = event.data;
      if (message.type === 'runPrompt') {
        animateRun(message);
      } else if (message.type === 'clear') {
        currentRun += 1;
        promptEl.textContent = 'Ketik prompt di terminal "AI Prompt".';
        fileEl.textContent = '-';
        badgeEl.textContent = 'READY';
        codeEl.textContent = '// Panel siap menerima prompt dari terminal.';
        updateGutter();
        resetSteps();
      } else if (message.type === 'stop') {
        currentRun += 1;
        badgeEl.textContent = 'STOPPED';
        steps.forEach((step) => step.classList.remove('active'));
      } else if (message.type === 'setSpeed') {
        typingDelay = message.speed || typingDelay;
      }
    });

    async function animateRun(message) {
      const runId = ++currentRun;
      typingDelay = message.speed || typingDelay;
      promptEl.textContent = message.prompt;
      fileEl.textContent = message.fileName;
      badgeEl.textContent = 'TYPING';
      codeEl.textContent = '';
      updateGutter();
      resetSteps();

      await markStep(0, runId);
      await markStep(1, runId);
      steps[2].classList.add('active');

      const text = message.code || '';
      for (let i = 0; i < text.length; i += 1) {
        if (runId !== currentRun) return;
        codeEl.textContent += text[i];
        if (text[i] === '\\n') {
          updateGutter();
        }
        editorEl.scrollTop = editorEl.scrollHeight;
        if (i % 4 === 0) {
          await wait(Math.max(3, typingDelay + Math.random() * typingDelay));
        }
      }

      updateGutter();
      steps[2].classList.remove('active');
      steps[2].classList.add('done');
      steps[3].classList.add('done');
      badgeEl.textContent = 'DONE';
    }

    async function markStep(index, runId) {
      steps[index].classList.add('active');
      await wait(450);
      if (runId !== currentRun) return;
      steps[index].classList.remove('active');
      steps[index].classList.add('done');
    }

    function resetSteps() {
      for (const step of steps) {
        step.classList.remove('active', 'done');
      }
    }

    function updateGutter() {
      const lineCount = Math.max(1, codeEl.textContent.split('\\n').length);
      gutterEl.textContent = Array.from({ length: lineCount }, (_, index) => index + 1).join('\\n');
    }

    function wait(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    updateGutter();
  </script>
</body>
</html>`;
}

function deactivate() {}

module.exports = {
  activate,
  deactivate
};
