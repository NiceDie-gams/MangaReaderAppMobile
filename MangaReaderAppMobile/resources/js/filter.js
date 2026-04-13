const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const loader = document.getElementById('global-loader');
const toast = document.getElementById('global-toast');
const pageLoader = document.getElementById('page-loader');
const pageLoaderMessage = document.getElementById('page-loader-message');

const showLoader = (state) => {
    loader?.classList.toggle('hidden', !state);

    // Show full-screen overlay while async title filtering is running.
    if (document.getElementById('filter-form')) {
        pageLoader?.classList.toggle('hidden', !state);
        pageLoaderMessage?.classList.toggle('hidden', !state);
        loader?.classList.add('hidden');
    } else {
        pageLoaderMessage?.classList.add('hidden');
    }
};
const showToast = (message) => {
    if (!toast) return;
    toast.textContent = message;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 2500);
};

const renderTitles = (titles) => {
    const grid = document.querySelector('.titles-grid');
    if (!grid) return;
    grid.innerHTML = titles.map((title) => `
        <a href="/title/${title.slug}" class="overflow-hidden rounded bg-white shadow">
            <img src="${title.cover_image}" class="h-56 w-full object-cover" alt="${title.title}">
            <div class="p-2 text-sm font-medium">${title.title}</div>
        </a>
    `).join('');
};

const collectFilters = () => {
    const form = document.getElementById('filter-form');
    if (!form) return null;
    const fd = new FormData(form);
    const params = new URLSearchParams();
    fd.forEach((value, key) => {
        if (value) params.append(key, value.toString());
    });
    return params;
};

const fetchTitles = async () => {
    const params = collectFilters();
    if (!params) return;
    showLoader(true);
    try {
        const response = await fetch(`/api/titles?${params.toString()}`, { headers: { Accept: 'application/json' } });
        const data = await response.json();
        renderTitles(data.data || []);
    } catch {
        showToast('Не удалось загрузить каталог');
    } finally {
        showLoader(false);
    }
};

document.getElementById('search-input')?.addEventListener('input', fetchTitles);
document.getElementById('apply-filters')?.addEventListener('click', (e) => {
    e.preventDefault();
    fetchTitles();
});

document.getElementById('clear-filters')?.addEventListener('click', () => {
    document.getElementById('filter-form')?.reset();
    fetchTitles();
});

document.getElementById('filters-toggle')?.addEventListener('click', () => {
    document.getElementById('filters-panel')?.classList.toggle('hidden');
});

document.getElementById('favorite-btn')?.addEventListener('click', async (e) => {
    const button = e.currentTarget;
    const titleId = button.dataset.titleId;
    const favorited = button.dataset.favorited === '1';
    showLoader(true);
    try {
        await fetch(`/favorites/${titleId}`, {
            method: favorited ? 'DELETE' : 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
        });
        button.dataset.favorited = favorited ? '0' : '1';
        button.textContent = favorited ? 'В избранное' : 'В избранном';
    } catch {
        showToast('Ошибка при обновлении избранного');
    } finally {
        showLoader(false);
    }
});

document.querySelectorAll('.favorite-remove').forEach((button) => {
    button.addEventListener('click', async () => {
        try {
            await fetch(`/favorites/${button.dataset.titleId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
            });
            button.closest('div.overflow-hidden')?.remove();
        } catch {
            showToast('Не удалось удалить из избранного');
        }
    });
});

const commentsList = document.getElementById('comments-list');
const loadComments = async () => {
    if (!commentsList) return;
    const titleId = commentsList.dataset.titleId;
    const response = await fetch(`/api/title/${titleId}/comments`);
    const comments = await response.json();
    commentsList.innerHTML = comments.map((comment) => `
        <div class="rounded border p-2">
            <div class="text-xs text-slate-500">${comment.user?.name ?? 'Пользователь'} - ${new Date(comment.created_at).toLocaleString()}</div>
            <div>${comment.content}</div>
        </div>
    `).join('');
};
loadComments();

document.getElementById('comment-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const titleId = commentsList?.dataset.titleId;
    const content = document.getElementById('comment-content')?.value ?? '';
    if (!content.trim()) return;
    try {
        await fetch('/comments', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
            body: JSON.stringify({ title_id: titleId, content }),
        });
        document.getElementById('comment-content').value = '';
        await loadComments();
    } catch {
        showToast('Ошибка отправки комментария');
    }
});

const chapterImage = document.getElementById('chapter-image');
if (chapterImage) {
    chapterImage.addEventListener('click', async (e) => {
        const rect = chapterImage.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const goNext = clickX > rect.width / 2;
        const chapterId = chapterImage.dataset.chapterId;
        const currentPage = Number(chapterImage.dataset.page);
        const targetPage = goNext ? currentPage + 1 : currentPage - 1;

        try {
            const response = await fetch(`/api/chapter/${chapterId}/page/${targetPage}`);
            if (!response.ok) throw new Error();
            const data = await response.json();
            chapterImage.src = data.image_path;
            chapterImage.dataset.page = String(data.page_number);
            history.pushState({}, '', `/title/${data.title_slug}/chapter/${chapterId}?page=${data.page_number}`);
        } catch {
            showToast('Достигнут край главы');
        }
    });
}
