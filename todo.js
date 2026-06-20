// ── TO-DO LIST FLOATING WIDGET ──────────────────────────────

(function () {
  // ── STATE ────────────────────────────────────────────────
  let todos = JSON.parse(localStorage.getItem('portfolio_todos') || '[]');
  let filter = 'all'; // all | active | done

  function save() {
    localStorage.setItem('portfolio_todos', JSON.stringify(todos));
  }

  // ── INJECT HTML ──────────────────────────────────────────
  document.body.insertAdjacentHTML('beforeend', `
    <div id="todo-fab" title="To-Do List">✏️</div>

    <div id="todo-panel" class="todo-hidden">
      <div id="todo-header">
        <span>📝 To-Do List</span>
        <button id="todo-close">✕</button>
      </div>

      <div id="todo-input-row">
        <input id="todo-input" type="text" placeholder="Tambah tugas baru…" maxlength="80">
        <button id="todo-add">+</button>
      </div>

      <div id="todo-filters">
        <button class="f-btn active" data-filter="all">Semua</button>
        <button class="f-btn" data-filter="active">Aktif</button>
        <button class="f-btn" data-filter="done">Selesai</button>
      </div>

      <ul id="todo-list"></ul>

      <div id="todo-footer">
        <span id="todo-count"></span>
        <button id="todo-clear">Hapus selesai</button>
      </div>
    </div>
  `);

  // ── INJECT STYLES ────────────────────────────────────────
  document.head.insertAdjacentHTML('beforeend', `<style>
    #todo-fab {
      position: fixed; bottom: 2rem; right: 2rem; z-index: 999;
      width: 54px; height: 54px;
      background: #1a1a2e; color: #fff;
      border-radius: 50%; border: none;
      font-size: 1.4rem; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 6px 24px rgba(0,0,0,.25);
      transition: transform .2s, background .2s;
    }
    #todo-fab:hover { background: #c9a84c; transform: scale(1.08); }

    #todo-panel {
      position: fixed; bottom: 6rem; right: 2rem; z-index: 998;
      width: 320px;
      background: #f5f0e8;
      border-radius: 16px;
      box-shadow: 0 16px 48px rgba(0,0,0,.18);
      overflow: hidden;
      transform-origin: bottom right;
      transition: transform .25s cubic-bezier(.4,0,.2,1), opacity .25s;
    }
    .todo-hidden { transform: scale(.85); opacity: 0; pointer-events: none; }

    #todo-header {
      display: flex; justify-content: space-between; align-items: center;
      padding: .9rem 1rem;
      background: #1a1a2e; color: #fff;
      font-family: 'Playfair Display', serif;
      font-size: .95rem;
    }
    #todo-close {
      background: none; border: none; color: #fff;
      font-size: 1rem; cursor: pointer; opacity: .7;
      transition: opacity .15s;
    }
    #todo-close:hover { opacity: 1; }

    #todo-input-row {
      display: flex; gap: .5rem;
      padding: .75rem 1rem;
      border-bottom: 1px solid rgba(0,0,0,.08);
    }
    #todo-input {
      flex: 1; padding: .5rem .75rem;
      border: 1.5px solid rgba(0,0,0,.12); border-radius: 8px;
      font-family: 'DM Sans', sans-serif; font-size: .88rem;
      background: #fff; color: #1a1a2e;
      transition: border-color .2s;
    }
    #todo-input:focus { outline: none; border-color: #c9a84c; }
    #todo-add {
      width: 36px; height: 36px;
      background: #c9a84c; color: #fff;
      border: none; border-radius: 8px;
      font-size: 1.3rem; cursor: pointer;
      transition: background .2s, transform .15s;
    }
    #todo-add:hover { background: #1a1a2e; transform: scale(1.05); }

    #todo-filters {
      display: flex; gap: .4rem;
      padding: .6rem 1rem;
      border-bottom: 1px solid rgba(0,0,0,.08);
    }
    .f-btn {
      flex: 1; padding: .35rem 0;
      border: 1.5px solid rgba(0,0,0,.12); border-radius: 20px;
      background: none; font-family: 'DM Sans', sans-serif;
      font-size: .75rem; font-weight: 500; cursor: pointer;
      color: #7a7a8c; transition: all .2s;
    }
    .f-btn.active { background: #1a1a2e; color: #fff; border-color: #1a1a2e; }

    #todo-list {
      list-style: none; margin: 0; padding: .5rem 0;
      max-height: 240px; overflow-y: auto;
    }
    #todo-list::-webkit-scrollbar { width: 4px; }
    #todo-list::-webkit-scrollbar-thumb { background: #c9a84c; border-radius: 4px; }

    .todo-item {
      display: flex; align-items: center; gap: .6rem;
      padding: .55rem 1rem;
      transition: background .15s;
      animation: slideIn .2s ease;
    }
    .todo-item:hover { background: rgba(201,168,76,.1); }
    @keyframes slideIn {
      from { opacity: 0; transform: translateX(10px); }
      to   { opacity: 1; transform: translateX(0); }
    }

    .todo-item input[type="checkbox"] {
      width: 17px; height: 17px; accent-color: #c9a84c;
      cursor: pointer; flex-shrink: 0;
    }
    .todo-item span {
      flex: 1; font-family: 'DM Sans', sans-serif;
      font-size: .88rem; color: #1a1a2e;
      word-break: break-word;
    }
    .todo-item.done span {
      text-decoration: line-through; color: #aaa;
    }
    .todo-item button {
      background: none; border: none;
      color: #ccc; font-size: .9rem; cursor: pointer;
      transition: color .15s;
    }
    .todo-item button:hover { color: #e55; }

    #todo-footer {
      display: flex; justify-content: space-between; align-items: center;
      padding: .65rem 1rem;
      border-top: 1px solid rgba(0,0,0,.08);
      font-family: 'DM Sans', sans-serif; font-size: .75rem; color: #7a7a8c;
    }
    #todo-clear {
      background: none; border: none;
      color: #c9a84c; font-size: .75rem;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer; text-decoration: underline;
    }
    #todo-clear:hover { color: #1a1a2e; }

    #todo-empty {
      text-align: center; padding: 1.5rem;
      font-family: 'DM Sans', sans-serif;
      font-size: .85rem; color: #bbb;
    }
  </style>`);

  // ── RENDER ───────────────────────────────────────────────
  function render() {
    const list = document.getElementById('todo-list');
    const visible = todos.filter(t =>
      filter === 'all' ? true : filter === 'done' ? t.done : !t.done
    );

    list.innerHTML = visible.length === 0
      ? `<li id="todo-empty">${filter === 'done' ? 'Belum ada tugas selesai.' : 'Belum ada tugas. Yuk tambah!'}</li>`
      : visible.map(t => `
          <li class="todo-item ${t.done ? 'done' : ''}" data-id="${t.id}">
            <input type="checkbox" ${t.done ? 'checked' : ''}>
            <span>${t.text}</span>
            <button title="Hapus">🗑</button>
          </li>
        `).join('');

    // count active
    const active = todos.filter(t => !t.done).length;
    document.getElementById('todo-count').textContent =
      `${active} tugas aktif`;
  }

  // ── EVENTS ───────────────────────────────────────────────
  const fab   = document.getElementById('todo-fab');
  const panel = document.getElementById('todo-panel');
  const input = document.getElementById('todo-input');

  // toggle panel
  fab.addEventListener('click', () => {
    panel.classList.toggle('todo-hidden');
    if (!panel.classList.contains('todo-hidden')) input.focus();
  });
  document.getElementById('todo-close').addEventListener('click', () => {
    panel.classList.add('todo-hidden');
  });

  // add todo
  function addTodo() {
    const text = input.value.trim();
    if (!text) return;
    todos.unshift({ id: Date.now(), text, done: false });
    save(); render();
    input.value = '';
    input.focus();
  }
  document.getElementById('todo-add').addEventListener('click', addTodo);
  input.addEventListener('keydown', e => { if (e.key === 'Enter') addTodo(); });

  // list events (delegation)
  document.getElementById('todo-list').addEventListener('click', e => {
    const item = e.target.closest('.todo-item');
    if (!item) return;
    const id = Number(item.dataset.id);

    if (e.target.type === 'checkbox') {
      todos = todos.map(t => t.id === id ? { ...t, done: !t.done } : t);
      save(); render();
    }
    if (e.target.closest('button')) {
      todos = todos.filter(t => t.id !== id);
      save(); render();
    }
  });

  // filter buttons
  document.getElementById('todo-filters').addEventListener('click', e => {
    const btn = e.target.closest('.f-btn');
    if (!btn) return;
    filter = btn.dataset.filter;
    document.querySelectorAll('.f-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    render();
  });

  // clear done
  document.getElementById('todo-clear').addEventListener('click', () => {
    todos = todos.filter(t => !t.done);
    save(); render();
  });

  // initial render
  render();
})();
