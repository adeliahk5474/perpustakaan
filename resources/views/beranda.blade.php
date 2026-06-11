<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>SelasarBuku — Beranda</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<style>
:root {
    --cream: #faf6f0; --cream2: #f2ebe0; --cream3: #e8ddd0;
    --forest: #2d5a27; --forest2: #3d7a35; --forest3: #1a3d16;
    --gold: #c8952a; --gold2: #e8b84b; --gold3: #f5d680;
    --text: #2c1f0e; --text2: #5c4a32; --text3: #8c7a62;
}
body { background: var(--cream); color: var(--text); font-family: 'Segoe UI', system-ui, sans-serif; }

/* NAV */
.nav { background: var(--forest3); }
.ntab { color: #a0c898; border-radius: 20px; transition: all .15s; }
.ntab:hover { color: var(--gold3); }
.ntab.active { background: var(--forest2); color: #fff; }
.nsearch input { background: transparent; color: #fff; }
.nsearch input::placeholder { color: rgba(255,255,255,.35); }

/* HERO */
.hero { background: var(--forest); border-radius: 16px; }
.hero-tag { background: rgba(200,149,42,.2); color: var(--gold3); border-radius: 20px; }
.hstat { background: rgba(255,255,255,.08); border-radius: 10px; }
.hstat-n { color: var(--gold2); }
.hstat-l { color: #a0c898; }

/* CARDS */
.bcard { background: #fff; border: 1px solid var(--cream3); border-radius: 16px; transition: transform .15s, box-shadow .15s; }
.bcard:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(44,31,14,.1); }
.bc1 { background: #e8f0e4; } .bc2 { background: #f0ebe0; }
.bc3 { background: #e4ecf0; } .bc4 { background: #f0e8e8; }
.bc5 { background: #ece4f0; } .bc6 { background: #e4f0e8; }
.cat-Fiksi { background: #e8f0e4; color: #2d5a27; }
.cat-Sains { background: #e4ecf0; color: #1a5a7a; }
.cat-Teknologi { background: #e4e8f0; color: #1a2d5a; }
.cat-Sejarah { background: #f0ebe0; color: #5a3a1a; }
.cat-Psikologi { background: #ece4f0; color: #4a1a7a; }

/* SIDEBAR */
.citem { color: var(--text2); border-radius: 8px; transition: all .15s; }
.citem:hover { background: var(--cream2); }
.citem.active { background: var(--forest); color: #fff; }

/* PROFILE */
.prof-hero { background: var(--forest); border-radius: 16px; }
.pav { background: var(--gold2); color: var(--forest3); }
.pbadge { background: rgba(200,149,42,.2); color: var(--gold3); }
.pstat { background: #fff; border: 1px solid var(--cream3); border-radius: 10px; }
.psn { color: var(--forest); }

/* LOAN CARD */
.lcard { background: #fff; border: 1px solid var(--cream3); border-radius: 10px; transition: background .1s; }
.lcard:hover { background: var(--cream2); }

/* MODAL */
.mwrap { background: rgba(44,31,14,.55); }
.modal-box { background: #fff; border-radius: 16px; width: 440px; max-width: 100%; }

/* TOAST */
.toast-box { background: var(--forest3); border-left: 3px solid var(--gold2); border-radius: 10px; }
.toast-box i { color: var(--gold2); }

/* BUTTONS */
.btn-forest { background: var(--forest); color: #fff; }
.btn-forest:hover { background: var(--forest2); }
.btn-cream { background: var(--cream); color: var(--text2); border: 1px solid var(--cream3); }
.btn-cream:hover { background: var(--cream3); }

/* PAGE TABS */
.page { display: none; }
.page.active { display: block; }
</style>
</head>
<body>

{{-- ======================== NAVBAR ======================== --}}
<nav class="nav sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-5 flex items-center justify-between h-14">

        {{-- Logo --}}
        <a href="{{ route('beranda') }}" class="flex items-center gap-2 font-bold text-base no-underline" style="color:var(--gold2)">
            <i class="ti ti-books text-xl"></i> SelasarBuku
        </a>

        {{-- Tabs --}}
        <div class="flex gap-1">
            <button class="ntab active px-4 py-1.5 text-sm font-medium flex items-center gap-1.5" id="tab-home" onclick="goTab('home')">
                <i class="ti ti-home"></i> Beranda
            </button>
            <button class="ntab px-4 py-1.5 text-sm font-medium flex items-center gap-1.5" id="tab-kategori" onclick="goTab('kategori')">
                <i class="ti ti-category"></i> Kategori
            </button>
            <button class="ntab px-4 py-1.5 text-sm font-medium flex items-center gap-1.5" id="tab-profile" onclick="goTab('profile')">
                <i class="ti ti-user"></i> Profil
            </button>
        </div>

        {{-- Search + Avatar --}}
        <div class="flex items-center gap-3">
            <div class="nsearch flex items-center gap-2 px-3 py-1.5 rounded-full" style="background:rgba(255,255,255,.1)">
                <i class="ti ti-search text-sm" style="color:rgba(255,255,255,.4)"></i>
                <input id="searchInput" type="text" placeholder="Cari buku..."
                    class="text-xs outline-none w-36 border-none"
                    oninput="filterBooks(this.value)">
            </div>
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold cursor-pointer"
                 style="background:var(--gold2);color:var(--forest3)"
                 onclick="goTab('profile')">
                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
            </div>
        </div>
    </div>
</nav>

{{-- CSRF untuk fetch --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- ======================== ALERTS ======================== --}}
<div id="alertBox" class="max-w-7xl mx-auto px-5 mt-3" style="display:none">
    <div id="alertMsg" class="px-4 py-3 rounded-xl text-sm"></div>
</div>

{{-- ======================== HOME PAGE ======================== --}}
<div class="page active" id="page-home">
<div class="max-w-7xl mx-auto px-5 py-5 space-y-5">

    {{-- Hero --}}
    <div class="hero p-6">
        <div class="hero-tag inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 mb-3">
            <i class="ti ti-sparkles"></i> Selamat datang kembali!
        </div>
        <h2 class="text-xl font-semibold mb-1" style="color:#fff">
            Halo, {{ Auth::user()->nama }}! Mau baca apa hari ini?
        </h2>
        <p class="text-sm mb-4" style="color:#a0c898">Jelajahi koleksi buku pilihan perpustakaan kami</p>
        <div class="flex gap-3">
            <div class="hstat px-4 py-2 text-center min-w-[80px]">
                <div class="hstat-n text-xl font-bold" id="statTotalBuku">{{ $totalBuku }}</div>
                <div class="hstat-l text-xs mt-0.5 uppercase tracking-wider">Total Buku</div>
            </div>
            <div class="hstat px-4 py-2 text-center min-w-[80px]">
                <div class="hstat-n text-xl font-bold" id="statDipinjam">{{ $totalDipinjam }}</div>
                <div class="hstat-l text-xs mt-0.5 uppercase tracking-wider">Dipinjam</div>
            </div>
            <div class="hstat px-4 py-2 text-center min-w-[80px]">
                <div class="hstat-n text-xl font-bold" id="statTersedia">{{ $totalTersedia }}</div>
                <div class="hstat-l text-xs mt-0.5 uppercase tracking-wider">Tersedia</div>
            </div>
        </div>
    </div>

    {{-- Pinjaman Aktif --}}
    @if($pinjamanAktif->isNotEmpty())
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-sm flex items-center gap-2" style="color:var(--text)">
                <i class="ti ti-bookmark text-base" style="color:var(--forest2)"></i> Sedang Dipinjam
            </h3>
        </div>
        <div class="space-y-2" id="pinjamanAktifList">
            @foreach($pinjamanAktif as $loan)
            @php
                $sisa = now()->diffInDays($loan->tanggal_kembali, false);
                $dlClass = $sisa < 0 ? 'text-red-600' : ($sisa <= 3 ? 'text-yellow-600' : 'text-green-700');
                $dlText = $sisa < 0
                    ? 'Terlambat '.abs($sisa).' hari'
                    : ($sisa == 0 ? 'Hari ini!' : 'Sisa '.$sisa.' hari');
                $bgClass = ['bc1','bc2','bc3','bc4','bc5','bc6'][($loan->book->id - 1) % 6];
            @endphp
            <div class="lcard flex items-center gap-3 p-3 cursor-pointer"
                 onclick="openModal({{ $loan->book->id }})">
                <div class="{{ $bgClass }} w-11 h-14 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-book text-lg" style="color:var(--text3);opacity:.5"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm truncate" style="color:var(--text)">{{ $loan->book->judul }}</div>
                    <div class="text-xs mt-0.5" style="color:var(--text3)">{{ $loan->book->pengarang }}</div>
                    <div class="text-xs mt-1 flex items-center gap-1 font-semibold {{ $dlClass }}">
                        <i class="ti ti-calendar"></i> {{ $dlText }}
                    </div>
                </div>
                <form action="{{ route('loans.kembalikan', $loan) }}" method="POST"
                      onsubmit="return handleKembali(event, this, {{ $loan->book->id }})">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="btn-cream px-3 py-1.5 rounded-lg text-xs font-semibold flex-shrink-0"
                        onclick="event.stopPropagation()">
                        Kembalikan
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Grid Buku --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-sm flex items-center gap-2" style="color:var(--text)">
                <i class="ti ti-books text-base" style="color:var(--forest2)"></i> Koleksi Buku
            </h3>
            <span class="text-xs font-medium cursor-pointer hover:underline"
                  style="color:var(--forest2)" onclick="goTab('kategori')">
                Lihat semua &rarr;
            </span>
        </div>
        <div class="grid grid-cols-4 gap-3" id="homeGrid">
            @foreach($books as $book)
                @include('partials.book-card', ['book' => $book, 'pinjamanAktif' => $pinjamanAktif])
            @endforeach
        </div>
        <div id="homeEmpty" class="hidden text-center py-16" style="color:var(--text3)">
            <i class="ti ti-search-off text-4xl block mb-3" style="color:var(--cream3)"></i>
            <p class="text-sm">Tidak ada buku yang cocok.</p>
        </div>
    </div>

</div>
</div>

{{-- ======================== KATEGORI PAGE ======================== --}}
<div class="page" id="page-kategori">
<div class="max-w-7xl mx-auto px-5 py-5">
    <div class="grid gap-4" style="grid-template-columns:160px 1fr">

        {{-- Sidebar Kategori --}}
        <div class="bg-white rounded-2xl p-3 border h-fit" style="border-color:var(--cream3)">
            <div class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:var(--text3)">Genre</div>
            <div id="catSidebar">
                @php
                    $allCats = ['Semua', ...$kategoris->toArray()];
                    $catIcons = ['Semua'=>'ti-layout-grid','Fiksi'=>'ti-book','Sains'=>'ti-microscope','Teknologi'=>'ti-device-laptop','Sejarah'=>'ti-building-arch','Psikologi'=>'ti-brain'];
                @endphp
                @foreach($allCats as $cat)
                <div class="citem flex items-center gap-2 px-2.5 py-2 text-sm font-medium cursor-pointer mb-0.5 {{ $cat === 'Semua' ? 'active' : '' }}"
                     onclick="filterKat('{{ $cat }}', this)">
                    <i class="ti {{ $catIcons[$cat] ?? 'ti-book' }} text-base"></i>
                    {{ $cat }}
                    <span class="ml-auto text-xs px-1.5 py-0.5 rounded-full"
                          style="background:var(--cream2);color:var(--text3)">
                        {{ $cat === 'Semua' ? $books->count() : $books->where('kategori', $cat)->count() }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Grid Buku Kategori --}}
        <div>
            <div class="flex items-center mb-3">
                <h3 class="font-semibold text-sm flex items-center gap-2" id="catTitle" style="color:var(--text)">
                    <i class="ti ti-books" style="color:var(--forest2)"></i> Semua Buku
                </h3>
            </div>
            <div class="grid gap-3" style="grid-template-columns:repeat(5,1fr)" id="katGrid">
                @foreach($books as $book)
                    @include('partials.book-card', ['book' => $book, 'pinjamanAktif' => $pinjamanAktif])
                @endforeach
            </div>
            <div id="katEmpty" class="hidden text-center py-16" style="color:var(--text3)">
                <i class="ti ti-book-off text-4xl block mb-3" style="color:var(--cream3)"></i>
                <p class="text-sm">Tidak ada buku di kategori ini.</p>
            </div>
        </div>

    </div>
</div>
</div>

{{-- ======================== PROFILE PAGE ======================== --}}
<div class="page" id="page-profile">
<div class="max-w-4xl mx-auto px-5 py-5 space-y-4">

    {{-- Profile Hero --}}
    <div class="prof-hero flex items-center gap-4 p-5">
        <div class="pav w-14 h-14 rounded-full flex items-center justify-center text-xl font-bold flex-shrink-0"
             style="border:3px solid rgba(255,255,255,.2)">
            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
        </div>
        <div>
            <div class="text-lg font-semibold" style="color:#fff">{{ Auth::user()->nama }}</div>
            <div class="text-xs mt-0.5" style="color:#a0c898">{{ Auth::user()->email }}</div>
            <div class="pbadge inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full mt-1.5">
                <i class="ti ti-id-badge"></i> Member Aktif
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="pstat text-center p-4">
            <div class="psn text-2xl font-bold" id="profDipinjam">{{ $totalDipinjam }}</div>
            <div class="text-xs mt-1" style="color:var(--text3)">Sedang Dipinjam</div>
        </div>
        <div class="pstat text-center p-4">
            <div class="psn text-2xl font-bold" id="profKembali">
                {{ Auth::user()->loans()->where('status','kembali')->count() }}
            </div>
            <div class="text-xs mt-1" style="color:var(--text3)">Dikembalikan</div>
        </div>
        <div class="pstat text-center p-4">
            <div class="psn text-2xl font-bold">
                {{ Auth::user()->loans()->count() }}
            </div>
            <div class="text-xs mt-1" style="color:var(--text3)">Total Pinjaman</div>
        </div>
    </div>

    {{-- Riwayat --}}
    <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:var(--cream3)">
        <div class="flex items-center gap-2 px-4 py-3 border-b" style="border-color:var(--cream3)">
            <i class="ti ti-history" style="color:var(--forest2)"></i>
            <span class="font-semibold text-sm" style="color:var(--text)">Riwayat Peminjaman</span>
        </div>
        @php
            $riwayat = Auth::user()->loans()->with('book')->latest()->get();
        @endphp
        @forelse($riwayat as $loan)
        @php
            $bgClass = ['bc1','bc2','bc3','bc4','bc5','bc6'][($loan->book->id - 1) % 6];
            $sisa = now()->diffInDays($loan->tanggal_kembali, false);
        @endphp
        <div class="flex items-center gap-3 px-4 py-2.5 border-b last:border-0"
             style="border-color:var(--cream3)">
            <div class="{{ $bgClass }} w-9 h-11 rounded-md flex items-center justify-center flex-shrink-0">
                <i class="ti ti-book text-sm" style="color:var(--text3);opacity:.5"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-xs truncate" style="color:var(--text)">{{ $loan->book->judul }}</div>
                <div class="text-xs mt-0.5" style="color:var(--text3)">
                    @if($loan->status === 'dipinjam')
                        Tenggat: {{ $loan->tanggal_kembali->format('d M Y') }}
                    @else
                        Dikembalikan: {{ $loan->tanggal_kembali->format('d M Y') }}
                    @endif
                </div>
            </div>
            @if($loan->status === 'dipinjam')
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:#e8f0e4;color:var(--forest)">
                    Dipinjam
                </span>
                <form action="{{ route('loans.kembalikan', $loan) }}" method="POST"
                      onsubmit="return handleKembali(event, this, {{ $loan->book->id }})">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-cream px-2.5 py-1 rounded-lg text-xs font-semibold ml-2">
                        Kembalikan
                    </button>
                </form>
            @else
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:var(--cream2);color:var(--text3)">
                    Dikembalikan
                </span>
            @endif
        </div>
        @empty
        <div class="text-center py-12" style="color:var(--text3)">
            <i class="ti ti-book-2 text-3xl block mb-3" style="color:var(--cream3)"></i>
            <p class="text-sm">Belum ada riwayat peminjaman.</p>
        </div>
        @endforelse
    </div>

</div>
</div>

{{-- ======================== MODAL ======================== --}}
<div class="mwrap hidden fixed inset-0 z-50 flex items-center justify-center p-5"
     id="modalWrap" onclick="if(event.target===this)closeModal()">
    <div class="modal-box overflow-hidden shadow-2xl">
        <div class="h-36 flex items-center justify-center" id="modalCover"></div>
        <div class="p-5">
            <div class="font-semibold text-base mb-1" id="modalTitle" style="color:var(--text)"></div>
            <div class="text-xs mb-3" id="modalAuthor" style="color:var(--text3)"></div>
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="rounded-lg p-2" style="background:var(--cream)">
                    <div class="text-xs font-semibold uppercase tracking-wide" style="color:var(--text3)">Kategori</div>
                    <div class="font-semibold text-xs mt-0.5" id="modalKat" style="color:var(--text)"></div>
                </div>
                <div class="rounded-lg p-2" style="background:var(--cream)">
                    <div class="text-xs font-semibold uppercase tracking-wide" style="color:var(--text3)">Stok</div>
                    <div class="font-semibold text-xs mt-0.5" id="modalStok" style="color:var(--text)"></div>
                </div>
                <div class="rounded-lg p-2" style="background:var(--cream)">
                    <div class="text-xs font-semibold uppercase tracking-wide" style="color:var(--text3)">Pengarang</div>
                    <div class="font-semibold text-xs mt-0.5" id="modalPengarang" style="color:var(--text)"></div>
                </div>
                <div class="rounded-lg p-2" style="background:var(--cream)">
                    <div class="text-xs font-semibold uppercase tracking-wide" style="color:var(--text3)">Kategori</div>
                    <div class="font-semibold text-xs mt-0.5" id="modalKat2" style="color:var(--text)"></div>
                </div>
            </div>
            <p class="text-xs leading-relaxed mb-4" id="modalSynopsis" style="color:var(--text2)"></p>
            <div class="flex gap-2">
                <button id="btnModalPinjam"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold border-none cursor-pointer">
                </button>
                <button onclick="closeModal()"
                    class="btn-cream px-4 py-2.5 rounded-xl text-sm font-semibold cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ======================== TOAST ======================== --}}
<div class="toast-box hidden fixed bottom-5 right-5 z-50 flex items-center gap-2 px-4 py-2.5 text-sm font-medium"
     id="toastBox" style="color:#fff">
    <i class="ti ti-check text-base" id="toastIcon"></i>
    <span id="toastMsg"></span>
</div>

{{-- ======================== DATA JSON untuk JS ======================== --}}
<script>
const BOOKS = @json($books->map(fn($b) => [
    'id'        => $b->id,
    'judul'     => $b->judul,
    'pengarang' => $b->pengarang,
    'kategori'  => $b->kategori,
    'stok'      => $b->jumlah_stok,
    'bg'        => 'bc'.(($b->id - 1) % 6 + 1),
]));

const LOANS_AKTIF = @json($pinjamanAktif->pluck('book_id'));

const ROUTES = {
    pinjam    : "{{ route('loans.store') }}",
    kembali   : "{{ url('/loans') }}",
    beranda   : "{{ route('beranda') }}",
    csrfToken : "{{ csrf_token() }}",
};
</script>

<script>
// ============ TABS ============
function goTab(tab) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.ntab').forEach(t => t.classList.remove('active'));
    document.getElementById('page-' + tab).classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}

// ============ FILTER SEARCH (client-side) ============
function filterBooks(q) {
    q = q.toLowerCase();
    const cards = document.querySelectorAll('#homeGrid .bcard-wrap');
    let visible = 0;
    cards.forEach(c => {
        const match = c.dataset.judul.includes(q) || c.dataset.pengarang.includes(q);
        c.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('homeEmpty').classList.toggle('hidden', visible > 0);
}

// ============ FILTER KATEGORI ============
function filterKat(cat, el) {
    document.querySelectorAll('#catSidebar .citem').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('catTitle').innerHTML =
        `<i class="ti ti-books" style="color:var(--forest2)"></i> ${cat === 'Semua' ? 'Semua Buku' : cat}`;

    const cards = document.querySelectorAll('#katGrid .bcard-wrap');
    let visible = 0;
    cards.forEach(c => {
        const match = cat === 'Semua' || c.dataset.kategori === cat;
        c.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('katEmpty').classList.toggle('hidden', visible > 0);
}

// ============ MODAL ============
function openModal(id) {
    const b = BOOKS.find(x => x.id === id);
    if (!b) return;
    const isp = LOANS_AKTIF.includes(id);

    document.getElementById('modalCover').className = `h-36 flex items-center justify-center ${b.bg}`;
    document.getElementById('modalCover').innerHTML = `<i class="ti ti-book" style="font-size:52px;color:var(--text3);opacity:.4"></i>`;
    document.getElementById('modalTitle').textContent     = b.judul;
    document.getElementById('modalAuthor').textContent    = b.pengarang;
    document.getElementById('modalKat').textContent       = b.kategori;
    document.getElementById('modalKat2').textContent      = b.kategori;
    document.getElementById('modalPengarang').textContent = b.pengarang;
    document.getElementById('modalStok').textContent      = b.stok > 0 ? b.stok + ' buku' : 'Habis';
    document.getElementById('modalSynopsis').textContent  = 'Klik Pinjam untuk meminjam buku ini. Tenggat pengembalian 14 hari sejak tanggal pinjam.';

    const btn = document.getElementById('btnModalPinjam');
    if (isp) {
        btn.textContent  = 'Sedang Dipinjam';
        btn.style.cssText = 'background:var(--cream2);color:var(--text3);cursor:default';
        btn.onclick = null;
    } else if (b.stok > 0) {
        btn.textContent  = 'Pinjam Buku';
        btn.style.cssText = 'background:var(--forest);color:#fff;cursor:pointer';
        btn.onclick = () => doPinjam(id);
    } else {
        btn.textContent  = 'Stok Habis';
        btn.style.cssText = 'background:var(--cream3);color:var(--text3);cursor:not-allowed';
        btn.onclick = null;
    }

    document.getElementById('modalWrap').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modalWrap').classList.add('hidden');
}

// ============ PINJAM via fetch ============
function doPinjam(bookId) {
    fetch(ROUTES.pinjam, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': ROUTES.csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ book_id: bookId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, true);
            closeModal();
            // Reload halaman untuk update data real dari DB
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Gagal meminjam buku.', false);
        }
    })
    .catch(() => showToast('Terjadi kesalahan.', false));
}

// ============ KEMBALI via fetch ============
function handleKembali(event, form, bookId) {
    event.preventDefault();
    const url = form.action;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': ROUTES.csrfToken,
            'X-HTTP-Method-Override': 'PATCH',
            'Accept': 'application/json',
        },
        body: new FormData(form)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, false);
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Gagal.', false);
        }
    });
    return false;
}

// ============ TOAST ============
function showToast(msg, ok = true) {
    const box  = document.getElementById('toastBox');
    const icon = document.getElementById('toastIcon');
    document.getElementById('toastMsg').textContent = msg;
    icon.className = ok ? 'ti ti-check text-base' : 'ti ti-bookmark-off text-base';
    box.style.borderLeftColor = ok ? 'var(--gold2)' : '#e8b84b';
    box.classList.remove('hidden');
    box.classList.add('flex');
    setTimeout(() => { box.classList.add('hidden'); box.classList.remove('flex'); }, 2800);
}

// ESC close modal
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>

</body>
</html>