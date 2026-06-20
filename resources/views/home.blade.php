<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SelasarBuku — Perpustakaan Digital</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --cream:#faf6f0;--cream2:#f2ebe0;--cream3:#e8ddd0;
  --forest:#2d5a27;--forest2:#3d7a35;--forest3:#1a3d16;
  --gold:#c8952a;--gold2:#e8b84b;--gold3:#f5d680;
  --text:#2c1f0e;--text2:#5c4a32;--text3:#8c7a62;
  --rad:10px;--rad2:16px
}
body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--cream);color:var(--text);font-size:14px;min-height:100vh}
.nav{background:var(--forest3);padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:54px;position:sticky;top:0;z-index:100}
.logo{color:var(--gold2);font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px;text-decoration:none}
.logo i{font-size:20px}
.nav-right{display:flex;align-items:center;gap:10px}
.btn-login{color:#a0c898;font-size:13px;font-weight:500;padding:7px 16px;border-radius:20px;text-decoration:none;transition:all .15s;border:1px solid rgba(255,255,255,.15)}
.btn-login:hover{color:var(--gold3);border-color:rgba(255,255,255,.3)}
.btn-daftar{background:var(--forest2);color:#fff;font-size:13px;font-weight:500;padding:7px 16px;border-radius:20px;text-decoration:none;transition:all .15s}
.btn-daftar:hover{background:var(--gold);color:#fff}
.hero{background:var(--forest);border-radius:var(--rad2);padding:32px;margin-bottom:24px;text-align:center;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;top:-40px;right:-40px;width:200px;height:200px;background:rgba(200,149,42,.08);border-radius:50%}
.hero::after{content:'';position:absolute;bottom:-40px;left:-20px;width:150px;height:150px;background:rgba(200,149,42,.06);border-radius:50%}
.hero-tag{display:inline-flex;align-items:center;gap:5px;background:rgba(200,149,42,.2);color:var(--gold3);font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;margin-bottom:12px}
.hero h1{color:#fff;font-size:26px;font-weight:700;margin-bottom:8px}
.hero p{color:#a0c898;font-size:14px;margin-bottom:20px}
.hero-search{display:flex;gap:8px;max-width:500px;margin:0 auto}
.hero-search input{flex:1;padding:10px 16px;border-radius:12px;border:none;outline:none;font-size:13px;color:var(--text)}
.hero-search button{padding:10px 20px;border-radius:12px;border:none;background:var(--gold2);color:var(--forest3);font-size:13px;font-weight:700;cursor:pointer;transition:all .15s}
.hero-search button:hover{background:var(--gold3)}
.hero-stats{display:flex;gap:12px;justify-content:center;margin-top:20px}
.hstat{background:rgba(255,255,255,.08);border-radius:var(--rad);padding:10px 20px;text-align:center}
.hstat-n{color:var(--gold2);font-size:20px;font-weight:700}
.hstat-l{color:#a0c898;font-size:10px;text-transform:uppercase;letter-spacing:.5px;margin-top:1px}
.content{max-width:1200px;margin:0 auto;padding:20px 24px}
.filter-row{display:flex;align-items:center;gap:8px;margin-bottom:16px;flex-wrap:wrap}
.filter-lbl{font-size:12px;color:var(--text3);font-weight:600}
.filter-btn{padding:5px 14px;border-radius:20px;font-size:11px;font-weight:600;cursor:pointer;text-decoration:none;border:none;transition:all .15s}
.filter-btn.active{background:var(--forest);color:#fff}
.filter-btn:not(.active){background:var(--cream2);color:var(--text2)}
.filter-btn:not(.active):hover{background:var(--cream3)}
.result-info{font-size:12px;color:var(--text3);margin-bottom:14px}
.result-info strong{color:var(--text);font-weight:600}
.books-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
.bcard{background:#fff;border-radius:var(--rad2);overflow:hidden;cursor:pointer;transition:transform .15s,box-shadow .15s;border:1px solid var(--cream3)}
.bcard:hover{transform:translateY(-3px);box-shadow:0 6px 20px rgba(44,31,14,.1)}
.bcov{height:120px;display:flex;align-items:center;justify-content:center}
.bc1{background:#e8f0e4}.bc2{background:#f0ebe0}.bc3{background:#e4ecf0}.bc4{background:#f0e8e8}.bc5{background:#ece4f0}.bc6{background:#e4f0e8}
.binfo{padding:10px}
.btitle{font-size:12px;font-weight:600;color:var(--text);margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.bauthor{font-size:11px;color:var(--text3);margin-bottom:6px}
.bcat{display:inline-block;font-size:10px;font-weight:600;padding:2px 7px;border-radius:10px;margin-bottom:6px}
.cf{background:#e8f0e4;color:#2d5a27}.cs{background:#e4ecf0;color:#1a5a7a}.ct{background:#e4e8f0;color:#1a2d5a}.cse{background:#f0ebe0;color:#5a3a1a}.cp{background:#ece4f0;color:#4a1a7a}
.bstok{font-size:10px;margin-bottom:8px}
.sok{color:var(--forest2);font-weight:600}.sno{color:#c0392b;font-weight:600}
.btn-pinjam-pub{width:100%;padding:7px;border-radius:8px;border:none;font-size:11px;font-weight:600;cursor:pointer;background:var(--cream2);color:var(--forest);transition:all .15s;text-decoration:none;display:block;text-align:center}
.btn-pinjam-pub:hover{background:var(--forest);color:#fff}
.btn-pinjam-dis{width:100%;padding:7px;border-radius:8px;border:none;font-size:11px;font-weight:600;background:var(--cream3);color:var(--text3);cursor:not-allowed}
.empty{text-align:center;padding:40px;color:var(--text3)}
.empty i{font-size:40px;display:block;margin-bottom:12px;color:var(--cream3)}
footer{background:var(--forest3);padding:16px 24px;text-align:center;font-size:12px;color:#a0c898;margin-top:32px}
</style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="nav">
  <a href="{{ route('home') }}" class="logo">
    <i class="ti ti-books"></i> SelasarBuku
  </a>
  <div class="nav-right">
    <a href="{{ route('login') }}" class="btn-login">
      <i class="ti ti-login"></i> Login
    </a>
    <a href="{{ route('register') }}" class="btn-daftar">
      <i class="ti ti-user-plus"></i> Daftar
    </a>
  </div>
</nav>

{{-- HERO --}}
<div style="max-width:1200px;margin:0 auto;padding:20px 24px 0">
  <div class="hero">
    <div class="hero-tag"><i class="ti ti-books"></i> Perpustakaan Digital</div>
    <h1>Selamat Datang di SelasarBuku</h1>
    <p>Temukan buku favoritmu dan pinjam dengan mudah</p>
    <form method="GET" action="{{ route('home') }}" class="hero-search">
      <input type="text" name="search" value="{{ request('search') }}"
             placeholder="Cari judul atau pengarang...">
      <button type="submit"><i class="ti ti-search"></i> Cari</button>
    </form>
    <div class="hero-stats">
      <div class="hstat">
        <div class="hstat-n">{{ $totalBuku }}</div>
        <div class="hstat-l">Total Buku</div>
      </div>
      <div class="hstat">
        <div class="hstat-n">{{ $kategoris->count() }}</div>
        <div class="hstat-l">Kategori</div>
      </div>
      <div class="hstat">
        <div class="hstat-n">{{ $books->where('jumlah_stok', '>', 0)->count() }}</div>
        <div class="hstat-l">Tersedia</div>
      </div>
    </div>
  </div>
</div>

{{-- KONTEN --}}
<div class="content">

  {{-- Filter Kategori --}}
  <div class="filter-row">
    <span class="filter-lbl">Filter:</span>
    <a href="{{ route('home', array_merge(request()->except('kategori','page'))) }}"
       class="filter-btn {{ !request('kategori') ? 'active' : '' }}">
      Semua
    </a>
    @foreach($kategoris as $kat)
      <a href="{{ route('home', array_merge(request()->except('page'), ['kategori' => $kat])) }}"
         class="filter-btn {{ request('kategori') === $kat ? 'active' : '' }}">
        {{ $kat }}
      </a>
    @endforeach
    @if(request('search') || request('kategori'))
      <a href="{{ route('home') }}" class="filter-btn" style="background:#fee2e2;color:#c0392b">
        <i class="ti ti-x"></i> Reset
      </a>
    @endif
  </div>

  {{-- Info Hasil --}}
  <div class="result-info">
    Menampilkan <strong>{{ $books->count() }}</strong> buku
    @if(request('search'))
      untuk "<strong>{{ request('search') }}</strong>"
    @endif
  </div>

  {{-- Grid Buku --}}
  @if($books->isEmpty())
    <div class="empty">
      <i class="ti ti-search-off"></i>
      <p>Buku tidak ditemukan.</p>
    </div>
  @else
    <div class="books-grid">
      @foreach($books as $book)
        @php
          $bgClass = ['bc1','bc2','bc3','bc4','bc5','bc6'][($book->id - 1) % 6];
          $catMap = ['Fiksi'=>'cf','Sains'=>'cs','Teknologi'=>'ct','Sejarah'=>'cse','Psikologi'=>'cp'];
          $catClass = $catMap[$book->kategori] ?? 'cf';
        @endphp
        <div class="bcard">
          <div class="bcov {{ $bgClass }}">
            <i class="ti ti-book" style="font-size:34px;color:var(--text3);opacity:.45"></i>
          </div>
          <div class="binfo">
            <div class="btitle" title="{{ $book->judul }}">{{ $book->judul }}</div>
            <div class="bauthor">{{ $book->pengarang }}</div>
            <span class="bcat {{ $catClass }}">{{ $book->kategori }}</span>
            <div class="bstok">
              @if($book->jumlah_stok > 0)
                <span class="sok"><i class="ti ti-check"></i> {{ $book->jumlah_stok }} tersedia</span>
              @else
                <span class="sno"><i class="ti ti-x"></i> Habis</span>
              @endif
            </div>
            @if($book->jumlah_stok > 0)
              <a href="{{ route('login') }}" class="btn-pinjam-pub">
                <i class="ti ti-login"></i> Login untuk Pinjam
              </a>
            @else
              <button class="btn-pinjam-dis" disabled>Stok Habis</button>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif

</div>

<footer>
  © {{ date('Y') }} SelasarBuku — Sistem Manajemen Perpustakaan Digital
</footer>

</body>
</html>