import { Editor } from '@tiptap/core';
import Image from '@tiptap/extension-image';
import { TableKit } from '@tiptap/extension-table';
import { Markdown } from '@tiptap/markdown';
import StarterKit from '@tiptap/starter-kit';

const buttonClass =
    'inline-flex h-9 min-w-9 cursor-pointer items-center justify-center rounded-lg px-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-200 focus-visible:outline-2 focus-visible:outline-husk-500 aria-pressed:bg-husk-700 aria-pressed:text-white disabled:cursor-not-allowed disabled:opacity-40';

const icon = (path) =>
    `<svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="${path}"/></svg>`;

const tools = [
    { label: 'Subjudul', html: 'H2', run: (chain) => chain.toggleHeading({ level: 2 }), active: ['heading', { level: 2 }] },
    { label: 'Subjudul kecil', html: 'H3', run: (chain) => chain.toggleHeading({ level: 3 }), active: ['heading', { level: 3 }] },
    null,
    { label: 'Tebal', html: '<strong>B</strong>', run: (chain) => chain.toggleBold(), active: ['bold'] },
    { label: 'Miring', html: '<em>I</em>', run: (chain) => chain.toggleItalic(), active: ['italic'] },
    { label: 'Coret', html: '<s>S</s>', run: (chain) => chain.toggleStrike(), active: ['strike'] },
    null,
    { label: 'Daftar', html: icon('M9 6h11M9 12h11M9 18h11M4.5 6h.01M4.5 12h.01M4.5 18h.01'), run: (chain) => chain.toggleBulletList(), active: ['bulletList'] },
    { label: 'Daftar bernomor', html: icon('M10 6h10M10 12h10M10 18h10M4 5l1-1v4M4 14h2l-2 3h2'), run: (chain) => chain.toggleOrderedList(), active: ['orderedList'] },
    { label: 'Kutipan', html: icon('M5 5v14M10 8h9M10 12h9M10 16h6'), run: (chain) => chain.toggleBlockquote(), active: ['blockquote'] },
    { label: 'Garis pemisah', html: icon('M4 12h16'), run: (chain) => chain.setHorizontalRule() },
    null,
    { label: 'Tautan', html: icon('M10 14a4 4 0 005.7 0l3-3a4 4 0 00-5.7-5.7l-1 1M14 10a4 4 0 00-5.7 0l-3 3A4 4 0 0011 18.7l1-1'), action: editLink, active: ['link'] },
    { label: 'Tabel', html: icon('M4 5h16v14H4zM4 10h16M10 5v14'), run: (chain) => chain.insertTable({ rows: 3, cols: 2, withHeaderRow: true }), active: ['table'] },
    { label: 'Tambah baris', html: '+ Baris', run: (chain) => chain.addRowAfter(), onlyIn: 'table' },
    { label: 'Tambah kolom', html: '+ Kolom', run: (chain) => chain.addColumnAfter(), onlyIn: 'table' },
    { label: 'Hapus baris', html: '− Baris', run: (chain) => chain.deleteRow(), onlyIn: 'table' },
    { label: 'Hapus kolom', html: '− Kolom', run: (chain) => chain.deleteColumn(), onlyIn: 'table' },
    { label: 'Hapus tabel', html: 'Hapus tabel', run: (chain) => chain.deleteTable(), onlyIn: 'table' },
    null,
    { label: 'Urungkan', html: icon('M9 14L4 9l5-5M4 9h10a6 6 0 010 12h-3'), run: (chain) => chain.undo(), can: (can) => can.undo() },
    { label: 'Ulangi', html: icon('M15 14l5-5-5-5M20 9H10a6 6 0 000 12h3'), run: (chain) => chain.redo(), can: (can) => can.redo() },
];

function editLink(editor) {
    const current = editor.getAttributes('link').href ?? '';
    const href = window.prompt('Alamat tautan, mis. /produk atau https://… (kosongkan untuk menghapus tautan)', current);

    if (href === null) {
        return;
    }

    const chain = editor.chain().focus().extendMarkRange('link');
    const url = href.trim();

    if (!url) {
        chain.unsetLink().run();
    } else if (editor.state.selection.empty && !current) {
        editor.chain().focus().insertContent({ type: 'text', text: url, marks: [{ type: 'link', attrs: { href: url } }] }).run();
    } else {
        chain.setLink({ href: url }).run();
    }
}

/**
 * Replace a Markdown textarea with a visual editor. The textarea stays the submitted field and
 * keeps its exact content until something is edited.
 */
export function mountRichTextEditor(textarea) {
    const wrapper = document.createElement('div');
    wrapper.className = 'relative mt-1.5 rounded-xl border border-stone-300 bg-white shadow-sm focus-within:outline-2 focus-within:outline-husk-500';

    const toolbar = document.createElement('div');
    toolbar.className = 'flex flex-wrap items-center gap-0.5 rounded-t-xl border-b border-stone-200 bg-stone-50 p-1.5';
    toolbar.setAttribute('role', 'toolbar');
    toolbar.setAttribute('aria-label', 'Format teks');

    const surface = document.createElement('div');
    surface.className = 'max-h-[36rem] overflow-y-auto px-4 py-1';

    const message = document.createElement('p');
    message.className = 'px-4 pb-2 text-xs font-medium text-red-600';
    message.textContent = 'Bagian ini wajib diisi.';
    message.hidden = true;

    textarea.before(wrapper);
    wrapper.append(toolbar, surface, textarea, message);

    const textareaClass = textarea.className;
    const visualClass = 'sr-only';
    const sourceClass = 'block w-full resize-y rounded-b-xl border-0 bg-white px-4 py-3 font-mono text-sm outline-none';
    let isVisual = true;
    let buttons = [];

    const editor = new Editor({
        element: surface,
        extensions: [
            // Markdown has no underline, and the website would show its notation as plain text.
            StarterKit.configure({ underline: false, link: { openOnClick: false, autolink: false } }),
            TableKit,
            Image,
            Markdown,
        ],
        content: textarea.value,
        contentType: 'markdown',
        editorProps: {
            attributes: {
                class: 'prose-kebun rich-text focus:outline-none',
                style: `min-height: ${Math.max(Number(textarea.rows) || 6, 6) * 1.5}rem`,
                role: 'textbox',
                'aria-multiline': 'true',
                'aria-label': textarea.labels?.[0]?.textContent.trim() ?? 'Teks',
            },
        },
        onUpdate: ({ editor: current }) => {
            textarea.value = current.isEmpty ? '' : current.getMarkdown().replace(/(\s|&nbsp;)+$/, '').trimStart();
            message.hidden = true;
        },
        onTransaction: () => refresh(),
    });

    buttons = tools.map((tool) => {
        if (!tool) {
            const divider = document.createElement('span');
            divider.className = 'mx-1 h-5 w-px bg-stone-300';
            toolbar.append(divider);

            return null;
        }

        const button = document.createElement('button');
        button.type = 'button';
        button.className = buttonClass;
        button.title = tool.label;
        button.setAttribute('aria-label', tool.label);
        button.innerHTML = tool.html;
        // Keep the text selection when a button is pressed.
        button.addEventListener('mousedown', (event) => event.preventDefault());
        button.addEventListener('click', () => (tool.action ? tool.action(editor) : tool.run(editor.chain().focus()).run()));
        toolbar.append(button);

        return { button, tool };
    }).filter(Boolean);

    const sourceButton = document.createElement('button');
    sourceButton.type = 'button';
    sourceButton.className = `${buttonClass} ml-auto`;
    sourceButton.textContent = 'Markdown';
    sourceButton.title = 'Tampilkan teks Markdown';
    sourceButton.setAttribute('aria-pressed', 'false');
    sourceButton.addEventListener('click', () => {
        if (!isVisual) {
            editor.commands.setContent(textarea.value, { contentType: 'markdown', emitUpdate: false });
        }

        setMode(!isVisual);
    });
    toolbar.append(sourceButton);

    function setMode(visual) {
        isVisual = visual;
        surface.hidden = !visual;
        textarea.className = visual ? visualClass : sourceClass;
        textarea.tabIndex = visual ? -1 : 0;
        sourceButton.setAttribute('aria-pressed', String(!visual));
        refresh();
    }

    function refresh() {
        buttons.forEach(({ button, tool }) => {
            button.hidden = Boolean(tool.onlyIn) && !editor.isActive(tool.onlyIn);
            button.disabled = !isVisual || (tool.can ? !tool.can(editor.can()) : false);

            if (tool.active) {
                button.setAttribute('aria-pressed', String(isVisual && editor.isActive(...tool.active)));
            }
        });
    }

    // Labels and browser validation focus the textarea, which is visually hidden in visual mode.
    textarea.addEventListener('focus', () => isVisual && editor.commands.focus());
    textarea.addEventListener('invalid', () => (message.hidden = false));
    textarea.addEventListener('input', () => (message.hidden = true));

    wrapper.classList.toggle('border-red-400', textareaClass.includes('border-red-400'));
    setMode(true);

    return editor;
}
