@extends('layouts.layout')

@section('title', 'Mozaik - Könyvek')

@section('content')
    <h1>📚 Könyveink</h1>
    <div id="book-search-container">
        <input type="text" id="book-search" placeholder="Keresés cím szerint...">
        <select id="book-select">
            <option value="">Válassz könyvet...</option>
        </select>
    </div>
    <ul id="konyvlista"></ul>
    <button id="konyvHozzaadBtn">➕ Új könyv hozzáadása</button>

    <div id="konyvHozzaadPopUp" class="konyvHozzaadPopUp">
        <div class="popup-content">
            <h2 id="popupTitle">Új könyv hozzáadása</h2>
            <form id="konyvHozzaadForm" enctype="multipart/form-data" action="{{ url('/create-book') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                <input type="hidden" name="id" id="bookId">
                <input type="text" name="title" id="bookTitle" placeholder="Cím" required>
                <input type="text" name="author" id="bookAuthor" placeholder="Szerző" required>
                <input type="number" name="pages" id="bookPages" placeholder="Oldalak száma">
                <textarea name="short_description" id="bookShort" placeholder="Rövid leírás"></textarea>
                <textarea name="description" id="bookDescription" placeholder="Teljes leírás"></textarea>
                <input type="file" name="cover_image" id="bookCover" accept="image/*">
                <button type="submit" id="saveBookBtn">Mentés</button>
                <button type="button" id="konyvHozzaadPopUpBezar">Mégse</button>
            </form>
        </div>
    </div>
    <div id="bookDetailPopup" class="popup-modal">
        <div class="popup-content">
            <div id="bookDetailContent"></div>
        </div>
    </div>
    <script>
        const searchInput = document.getElementById('book-search');
        const bookSelect = document.getElementById('book-select');
        let allBooks = [];
        async function loadBooks() {
            try {
                const response = await fetch('/books');
                const books = await response.json();
                allBooks = books;
                populateBookSelect(books);
                renderBookList(books)
            } catch (err) {
                console.error('Probléma történt a könyvek lekérésekor:', err);
                document.getElementById('konyvlista').innerHTML =
                    '<li style="color:red;">Probléma történt a könyvek lekérésekor</li>';
            }
        }

        function populateBookSelect(books) {
            bookSelect.innerHTML = '<option value="">Válassz könyvet...</option>';
            books.forEach(book => {
                const option = document.createElement('option');
                option.value = book.id;
                option.textContent = book.title;
                bookSelect.appendChild(option);
            });
        }

        function openEditBookPopup(book) {
            const popup = document.getElementById('konyvHozzaadPopUp');
            document.getElementById('popupTitle').textContent = 'Könyv szerkesztése';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('bookId').value = book.id;
            document.getElementById('bookTitle').value = book.title;
            document.getElementById('bookAuthor').value = book.author;
            document.getElementById('bookPages').value = book.pages ?? '';
            document.getElementById('bookShort').value = book.short_description ?? '';
            document.getElementById('bookDescription').value = book.description ?? '';
            popup.style.display = 'flex';
        }

        function showBookDetail(book) {
            const popup = document.getElementById('bookDetailPopup');
            const content = document.getElementById('bookDetailContent');

            content.innerHTML = `
                <h2>${book.title}</h2>
                <img src="/storage/${book.cover_image || 'coverimages/default.jpg'}" alt="${book.title}" style="width:200px; float:left; margin-right:15px; border-radius:6px;">
                <p><strong>Szerző:</strong> ${book.author}</p>
                <p><strong>Oldalak:</strong> ${book.pages ?? 'N/A'}</p>
                <p><strong>Rövid leírás:</strong> ${book.short_description ?? ''}</p>
                <p><strong>Teljes leírás:</strong> ${book.description ?? ''}</p>
                <div style="clear:both;"></div>
            `;

            popup.style.display = 'flex';
        }
        const popup = document.getElementById('bookDetailPopup');
        window.addEventListener('click', (e) => {
            if (e.target === popup) {
                popup.style.display = 'none';
            }
        });
        const konyvHozzaadPopUp = document.getElementById('konyvHozzaadPopUp');
        const openBtn = document.getElementById('konyvHozzaadBtn');
        const closeBtn = document.getElementById('konyvHozzaadPopUpBezar');

        openBtn.addEventListener('click', () => {
            const form = document.getElementById('konyvHozzaadForm');
            form.reset();

            document.getElementById('bookId').value = '';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('popupTitle').textContent = 'Új könyv hozzáadása';

            konyvHozzaadPopUp.style.display = 'flex';
        });
        closeBtn.addEventListener('click', () => konyvHozzaadPopUp.style.display = 'none');
        window.addEventListener('click', (e) => {
            if (e.target === konyvHozzaadPopUp) konyvHozzaadPopUp.style.display = 'none';
        });

        document.getElementById('konyvHozzaadForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const bookId = document.getElementById('bookId').value;
            const method = document.getElementById('formMethod').value;

            let url = '/create-book';
            let fetchOptions = { method: 'POST', body: formData };

            if (method === 'PUT' && bookId) {
                url = `/books/${bookId}`;
                formData.append('_method', 'PUT');
                fetchOptions = { method: 'POST', body: formData };
            }

            try {
                const response = await fetch(url, fetchOptions);
                if (!response.ok) throw new Error('Hiba a könyv mentésekor');
                form.reset();
                document.getElementById('konyvHozzaadPopUp').style.display = 'none';
                await loadBooks();
            } catch (err) {
                alert('Hiba a könyv mentésekor');
                console.error(err);
            }
        });
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            const filtered = allBooks.filter(book => book.title.toLowerCase().includes(query));
            renderBookList(filtered);
        });
        bookSelect.addEventListener('change', () => {
            const id = bookSelect.value;
            if (!id) {
                renderBookList(allBooks);
            } else {
                const selected = allBooks.filter(book => book.id == id);
                renderBookList(selected);
            }
        });

        function renderBookList(books) {
            const list = document.getElementById('konyvlista');
            list.innerHTML = '';

            if (books.length === 0) {
                list.innerHTML = '<li>Nem találtunk könyveket az adatbázisban :(</li>';
                return;
            }

            books.forEach(book => {
                const li = document.createElement('li');
                li.innerHTML = `
                        <div  class="book-info"><img src="/storage/${book.cover_image || 'coverimages/default.jpg'}" alt="${book.title}" width="100">
                        <h3>${book.title}</h3><br>
                        <em>írta ${book.author}</em>
                        <p>${book.short_description ?? ''}</p><br>
                        <small class="pages">${book.pages ? book.pages + ' oldal' : ''}</small>
                        <br>Létrehozva: ${new Date(book.created_at).toLocaleString()}<br>
                        Módosítva: ${new Date(book.updated_at).toLocaleString()}
                        <button class="editBtn">Szerkesztés</button>
                        <button class="delete-btn" data-id="${book.id}">❌</button><div>
                    `;
                li.querySelector('div').addEventListener('click', e => {
                    if (e.target.classList.contains('delete-btn')) return;
                    if (!e.target.classList.contains('editBtn')) {
                        showBookDetail(book);
                    }
                });
                const editButton = li.querySelector('.editBtn');
                if (editButton) {
                    editButton.addEventListener('click', e => {
                        e.stopPropagation();
                        openEditBookPopup(book);
                    });
                }
                const deleteBtn = li.querySelector('.delete-btn');
                deleteBtn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    if (!confirm(`Biztosan törlöd a(z) "${book.title}" könyvet?`)) return;

                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        const res = await fetch(`/books/${book.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        });

                        if (!res.ok) throw new Error('Nem sikerült törölni a könyvet');

                        li.remove();
                    } catch (err) {
                        alert('Hiba a törléskor');
                        console.error(err);
                    }
                });
                list.appendChild(li);
            });
        }
        document.addEventListener('DOMContentLoaded', loadBooks);
    </script>

@endsection
