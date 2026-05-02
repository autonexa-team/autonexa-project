
<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Lora:wght@400;600&display=swap');
  *{margin:0;padding:0;box-sizing:border-box}
  :root{
    --g50:#f0faf5;--g100:#d4f0e4;--g200:#a8e0c7;--g400:#4cb88a;--g500:#2da06e;--g600:#1e8058;--g700:#145a3e;
    --wh:#ffffff;--bg:#f4f7f5;--text1:#0e1f17;--text2:#3d5a4a;--text3:#6b8a78;--border:#d4e8dc;
    --font:'Plus Jakarta Sans',sans-serif;
  }
  body{font-family:var(--font);background:var(--bg);color:var(--text1);min-height:100vh;display:flex}

  .sidebar{width:220px;min-width:220px;background:var(--g700);height:100vh;position:sticky;top:0;display:flex;flex-direction:column;padding:0}
  .logo{padding:24px 20px 20px;border-bottom:1px solid rgba(255,255,255,0.1)}
  .logo-mark{display:flex;align-items:center;gap:10px}
  .logo-icon{width:34px;height:34px;background:var(--g400);border-radius:10px;display:flex;align-items:center;justify-content:center}
  .logo-icon svg{width:18px;height:18px;fill:#fff}
  .logo-text{font-family:'Lora',serif;font-size:18px;font-weight:600;color:#fff;letter-spacing:-0.3px}
  .logo-sub{font-size:10px;color:rgba(255,255,255,0.5);margin-top:1px;font-weight:400}
  
  .nav{flex:1;padding:16px 12px;display:flex;flex-direction:column;gap:2px}
  .nav-label{font-size:10px;font-weight:600;color:rgba(255,255,255,0.35);letter-spacing:1px;text-transform:uppercase;padding:10px 8px 6px}
  .nav-item{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:8px;cursor:pointer;transition:background 0.15s;color:rgba(255,255,255,0.65);font-size:13.5px;font-weight:500}
  .nav-item:hover{background:rgba(255,255,255,0.08);color:#fff}
  .nav-item.active{background:var(--g500);color:#fff}
  .nav-item svg{width:16px;height:16px;opacity:0.8;flex-shrink:0}
  .nav-item.active svg{opacity:1}
  .nav-badge{margin-left:auto;background:var(--g400);color:#fff;font-size:10px;font-weight:600;padding:2px 6px;border-radius:20px}
  
  .user-box{padding:14px 12px;border-top:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;gap:10px}
  .avatar{width:34px;height:34px;border-radius:50%;background:var(--g400);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0}
  .user-info p{font-size:13px;font-weight:600;color:#fff}
  .user-info span{font-size:11px;color:rgba(255,255,255,0.5)}
  
  .main{flex:1;overflow:auto;display:flex;flex-direction:column}
  .topbar{background:var(--wh);border-bottom:1px solid var(--border);padding:0 28px;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10}
  .topbar-left{display:flex;flex-direction:column}
  .topbar-left h2{font-size:16px;font-weight:700;color:var(--text1)}
  .topbar-left span{font-size:12px;color:var(--text3)}
  .topbar-right{display:flex;align-items:center;gap:12px}
  .tb-btn{display:flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:12.5px;font-weight:600;cursor:pointer;border:none;font-family:var(--font)}
  .tb-btn.ghost{background:transparent;border:1px solid var(--border);color:var(--text2)}
  .tb-btn.primary{background:var(--g500);color:#fff}
  .tb-btn svg{width:14px;height:14px}
  .notif-dot{width:8px;height:8px;background:#ff6b6b;border-radius:50%;margin-top:-8px;margin-left:-4px}
  
  .content{padding:24px 28px;display:flex;flex-direction:column;gap:20px}
  
  .kpi-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
  .kpi-card{background:var(--wh);border:1px solid var(--border);border-radius:14px;padding:18px 18px 14px;position:relative;overflow:hidden}
  .kpi-card.accent{background:linear-gradient(135deg,var(--g600) 0%,var(--g500) 100%);border-color:transparent}
  .kpi-label{font-size:11.5px;font-weight:500;color:var(--text3);margin-bottom:6px}
  .kpi-card.accent .kpi-label{color:rgba(255,255,255,0.7)}
  .kpi-val{font-size:22px;font-weight:700;color:var(--text1);font-family:'Lora',serif;letter-spacing:-0.5px}
  .kpi-card.accent .kpi-val{color:#fff}
  .kpi-sub{font-size:11px;margin-top:4px;display:flex;align-items:center;gap:4px}
  .kpi-sub.up{color:var(--g500)}
  .kpi-sub.down{color:#e05252}
  .kpi-card.accent .kpi-sub{color:rgba(255,255,255,0.65)}
  .kpi-deco{position:absolute;right:-10px;top:-10px;width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,0.08)}
  
  .two-col{display:grid;grid-template-columns:1.4fr 1fr;gap:16px}
  .panel{background:var(--wh);border:1px solid var(--border);border-radius:14px;padding:20px}
  .panel-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
  .panel-title{font-size:14px;font-weight:700;color:var(--text1)}
  .panel-link{font-size:12px;color:var(--g500);font-weight:600;cursor:pointer;background:none;border:none;font-family:var(--font)}
  
  .bar-chart{display:flex;flex-direction:column;gap:8px}
  .bar-row{display:flex;align-items:center;gap:10px}
  .bar-label{font-size:12px;color:var(--text3);width:70px;flex-shrink:0;text-align:right}
  .bar-track{flex:1;height:28px;background:var(--g50);border-radius:6px;overflow:hidden;position:relative}
  .bar-fill{height:100%;border-radius:6px;display:flex;align-items:center;justify-content:flex-end;padding-right:8px;font-size:11px;font-weight:600;color:#fff;transition:width 0.6s ease}
  .bar-amt{font-size:12px;color:var(--text2);font-weight:600;width:72px;text-align:right;flex-shrink:0}
  
  .donut-wrap{display:flex;flex-direction:column;align-items:center;gap:12px}
  .donut-center{position:relative;width:130px;height:130px}
  .donut-center svg{width:100%;height:100%}
  .donut-label{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center}
  .donut-label strong{font-size:18px;font-weight:700;color:var(--text1);font-family:'Lora',serif;display:block}
  .donut-label small{font-size:10px;color:var(--text3)}
  .legend-list{width:100%;display:flex;flex-direction:column;gap:7px}
  .legend-row{display:flex;align-items:center;gap:8px}
  .legend-dot{width:10px;height:10px;border-radius:3px;flex-shrink:0}
  .legend-name{flex:1;font-size:12px;color:var(--text2)}
  .legend-pct{font-size:12px;font-weight:600;color:var(--text1)}
  
  .three-col{display:grid;grid-template-columns:1.2fr 1fr 1fr;gap:16px}
  
  .tx-list{display:flex;flex-direction:column;gap:0}
  .tx-item{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)}
  .tx-item:last-child{border-bottom:none}
  .tx-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
  .tx-info{flex:1;min-width:0}
  .tx-name{font-size:13px;font-weight:600;color:var(--text1)}
  .tx-date{font-size:11px;color:var(--text3);margin-top:1px}
  .tx-amt{font-size:13px;font-weight:700}
  .tx-amt.in{color:var(--g500)}
  .tx-amt.out{color:#e05252}
  
  .invest-list{display:flex;flex-direction:column;gap:8px}
  .invest-item{padding:12px;border-radius:10px;border:1px solid var(--border);background:var(--g50)}
  .invest-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px}
  .invest-name{font-size:13px;font-weight:700;color:var(--text1)}
  .invest-tag{font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px}
  .tag-low{background:#eaf7ee;color:var(--g600)}
  .tag-med{background:#fff8e0;color:#b07a00}
  .tag-high{background:#ffeaea;color:#c0392b}
  .invest-desc{font-size:11.5px;color:var(--text3);line-height:1.5;margin-bottom:6px}
  .invest-bar{height:4px;background:var(--border);border-radius:2px;overflow:hidden;margin-bottom:4px}
  .invest-prog{height:100%;border-radius:2px}
  .invest-meta{display:flex;justify-content:space-between;font-size:11px;color:var(--text3)}
  
  .tips-list{display:flex;flex-direction:column;gap:8px}
  .tip-card{padding:12px;border-radius:10px;border-left:3px solid}
  .tip-card.green{border-color:var(--g400);background:var(--g50)}
  .tip-card.amber{border-color:#f0b429;background:#fffbee}
  .tip-card.blue{border-color:#3b9bdf;background:#f0f7ff}
  .tip-head{font-size:12.5px;font-weight:700;margin-bottom:3px}
  .tip-card.green .tip-head{color:var(--g700)}
  .tip-card.amber .tip-head{color:#7a5500}
  .tip-card.blue .tip-head{color:#1a5ea0}
  .tip-body{font-size:11.5px;line-height:1.5;color:var(--text2)}
  
  .lite-list{display:flex;flex-direction:column;gap:6px}
  .lite-item{display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;border:1px solid var(--border);cursor:pointer;transition:background 0.15s}
  .lite-item:hover{background:var(--g50)}
  .lite-icon{width:32px;height:32px;border-radius:8px;background:var(--g100);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
  .lite-info{flex:1}
  .lite-title{font-size:12.5px;font-weight:600;color:var(--text1)}
  .lite-sub{font-size:11px;color:var(--text3);margin-top:1px}
  .lite-arr{font-size:14px;color:var(--text3)}

  .budget-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
  .bud-card{padding:14px;border-radius:12px;border:1px solid var(--border);background:var(--wh)}
  .bud-cat{font-size:12px;font-weight:600;color:var(--text2);margin-bottom:2px}
  .bud-used{font-size:18px;font-weight:700;color:var(--text1);font-family:'Lora',serif}
  .bud-total{font-size:11px;color:var(--text3);margin-bottom:8px}
  .bud-track{height:6px;background:var(--border);border-radius:3px;overflow:hidden}
  .bud-prog{height:100%;border-radius:3px;transition:width 0.5s}
  .prog-ok{background:var(--g400)}
  .prog-warn{background:#f0b429}
  .prog-over{background:#e05252}

  .month-tabs{display:flex;gap:4px;background:var(--g50);padding:4px;border-radius:10px;margin-bottom:14px}
  .m-tab{flex:1;text-align:center;font-size:11.5px;font-weight:600;padding:5px;border-radius:7px;cursor:pointer;color:var(--text3)}
  .m-tab.active{background:var(--wh);color:var(--g600);border:1px solid var(--border)}

  .sparkline{width:100%;height:50px;margin-top:8px}
</style>

<div style="display:flex;min-height:600px">
  <div class="sidebar">
    <div class="logo">
      <div class="logo-mark">
        <div class="logo-icon">
          <svg viewBox="0 0 20 20"><path d="M10 2C5.58 2 2 5.58 2 10s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm1 11H9V9h2v4zm0-6H9V5h2v2z"/></svg>
        </div>
        <div>
          <div class="logo-text">FinWise</div>
          <div class="logo-sub">Smart Finance Hub</div>
        </div>
      </div>
    </div>

    <nav class="nav">
      <div class="nav-label">Utama</div>
      <div class="nav-item active">
        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 8a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm8-8a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V4zm0 8a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
        Dashboard
      </div>
      <div class="nav-item">
        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM2 11v5a2 2 0 002 2h12a2 2 0 002-2v-5H2z"/></svg>
        Mutasi Harian
        <span class="nav-badge">3</span>
      </div>
      <div class="nav-item">
        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2h10.586l-2.293 2.293a1 1 0 101.414 1.414l4-4a1 1 0 000-1.414l-4-4a1 1 0 10-1.414 1.414L13.586 3H3zm14 14a1 1 0 000-2H6.414l2.293-2.293a1 1 0 10-1.414-1.414l-4 4a1 1 0 000 1.414l4 4a1 1 0 001.414-1.414L6.414 17H17z" clip-rule="evenodd"/></svg>
        Pemasukan
      </div>
      <div class="nav-item">
        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.077 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.077-2.354-1.253V5z" clip-rule="evenodd"/></svg>
        Smart Budget
      </div>
      
      <div class="nav-label" style="margin-top:8px">Edukasi</div>
      <div class="nav-item">
        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.396 0 2.7.37 3.8 1.016A7.968 7.968 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.969 7.969 0 0114.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
        Literasi Finance
      </div>
      <div class="nav-item">
        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zm6-4a1 1 0 011-1h2a1 1 0 011 1v13a1 1 0 01-1 1h-2a1 1 0 01-1-1V3z"/></svg>
        Rekomendasi Investasi
      </div>
    </nav>

    <div class="user-box">
      <div class="avatar">AR</div>
      <div class="user-info">
        <p>Andi Rahmat</p>
        <span>Premium Plan</span>
      </div>
    </div>
  </div>

  <div class="main">
    <div class="topbar">
      <div class="topbar-left">
        <h2>Dashboard Keuangan</h2>
        <span>Sabtu, 18 April 2026</span>
      </div>
      <div class="topbar-right">
        <button class="tb-btn ghost" style="display:flex;align-items:center;gap:6px">
          <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-4 4A1 1 0 016 19v-7.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/></svg>
          Filter
        </button>
        <div style="position:relative;cursor:pointer">
          <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="var(--text2)" stroke-width="1.5"><path d="M10 2a7 7 0 017 7 7.003 7.003 0 01-2.05 4.95l2.1 2.1-1.41 1.41-2.1-2.1A7 7 0 1110 2z"/></svg>
        </div>
        <div style="position:relative;cursor:pointer">
          <svg width="22" height="22" viewBox="0 0 20 20" fill="none" stroke="var(--text2)" stroke-width="1.5"><path d="M15 17H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2z"/><path d="M13 5V4a1 1 0 00-1-1H8a1 1 0 00-1 1v1"/></svg>
          <div class="notif-dot"></div>
        </div>
        <button class="tb-btn primary" onclick="alert('Tambah mutasi!')">
          <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
          Tambah Mutasi
        </button>
      </div>
    </div>

    <div class="content">
      <div class="kpi-row">
        <div class="kpi-card accent">
          <div class="kpi-deco"></div>
          <div class="kpi-label">Total Saldo</div>
          <div class="kpi-val">Rp 14,8 jt</div>
          <div class="kpi-sub">
            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
            +12,4% dari bulan lalu
          </div>
        </div>
        <div class="kpi-card">
          <div class="kpi-label">Pemasukan Bulan Ini</div>
          <div class="kpi-val" style="color:var(--g500)">Rp 8,2 jt</div>
          <div class="kpi-sub up">
            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
            +5% vs Maret
          </div>
        </div>
        <div class="kpi-card">
          <div class="kpi-label">Pengeluaran Bulan Ini</div>
          <div class="kpi-val" style="color:#e05252">Rp 5,4 jt</div>
          <div class="kpi-sub down">
            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            -3% vs Maret
          </div>
        </div>
        <div class="kpi-card">
          <div class="kpi-label">Dana Investasi Aktif</div>
          <div class="kpi-val" style="color:var(--text1)">Rp 3,5 jt</div>
          <div class="kpi-sub up">
            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
            Yield +8,2% YTD
          </div>
        </div>
      </div>

      <div class="two-col">
        <div class="panel">
          <div class="panel-hd">
            <span class="panel-title">Pengeluaran per Kategori</span>
            <button class="panel-link">Lihat detail</button>
          </div>
          <div class="month-tabs">
            <div class="m-tab active">Apr</div>
            <div class="m-tab">Mar</div>
            <div class="m-tab">Feb</div>
            <div class="m-tab">Jan</div>
            <div class="m-tab">Des</div>
          </div>
          <div class="bar-chart">
            <div class="bar-row">
              <span class="bar-label">Makanan</span>
              <div class="bar-track"><div class="bar-fill" style="width:72%;background:var(--g400)">72%</div></div>
              <span class="bar-amt">Rp 1,8jt</span>
            </div>
            <div class="bar-row">
              <span class="bar-label">Transport</span>
              <div class="bar-track"><div class="bar-fill" style="width:48%;background:var(--g500)">48%</div></div>
              <span class="bar-amt">Rp 950rb</span>
            </div>
            <div class="bar-row">
              <span class="bar-label">Belanja</span>
              <div class="bar-track"><div class="bar-fill" style="width:35%;background:#f0b429">35%</div></div>
              <span class="bar-amt">Rp 720rb</span>
            </div>
            <div class="bar-row">
              <span class="bar-label">Tagihan</span>
              <div class="bar-track"><div class="bar-fill" style="width:28%;background:var(--g600)">28%</div></div>
              <span class="bar-amt">Rp 600rb</span>
            </div>
            <div class="bar-row">
              <span class="bar-label">Hiburan</span>
              <div class="bar-track"><div class="bar-fill" style="width:18%;background:#3b9bdf">18%</div></div>
              <span class="bar-amt">Rp 380rb</span>
            </div>
            <div class="bar-row">
              <span class="bar-label">Lainnya</span>
              <div class="bar-track"><div class="bar-fill" style="width:12%;background:#9b8bdf">12%</div></div>
              <span class="bar-amt">Rp 240rb</span>
            </div>
          </div>
        </div>

        <div class="panel">
          <div class="panel-hd">
            <span class="panel-title">Distribusi Keuangan</span>
          </div>
          <div class="donut-wrap">
            <div class="donut-center">
              <svg viewBox="0 0 130 130">
                <circle cx="65" cy="65" r="50" fill="none" stroke="var(--border)" stroke-width="18"/>
                <circle cx="65" cy="65" r="50" fill="none" stroke="var(--g400)" stroke-width="18" stroke-dasharray="226 314" stroke-dashoffset="78" stroke-linecap="round"/>
                <circle cx="65" cy="65" r="50" fill="none" stroke="#f0b429" stroke-width="18" stroke-dasharray="101 314" stroke-dashoffset="-148" stroke-linecap="round"/>
                <circle cx="65" cy="65" r="50" fill="none" stroke="#3b9bdf" stroke-width="18" stroke-dasharray="63 314" stroke-dashoffset="-249" stroke-linecap="round"/>
                <circle cx="65" cy="65" r="50" fill="none" stroke="#e05252" stroke-width="18" stroke-dasharray="50 314" stroke-dashoffset="-312" stroke-linecap="round"/>
              </svg>
              <div class="donut-label"><strong>Rp 14,8jt</strong><small>Total</small></div>
            </div>
            <div class="legend-list">
              <div class="legend-row"><div class="legend-dot" style="background:var(--g400)"></div><span class="legend-name">Tabungan</span><span class="legend-pct">36%</span></div>
              <div class="legend-row"><div class="legend-dot" style="background:#f0b429"></div><span class="legend-name">Pengeluaran</span><span class="legend-pct">20%</span></div>
              <div class="legend-row"><div class="legend-dot" style="background:#3b9bdf"></div><span class="legend-name">Investasi</span><span class="legend-pct">12%</span></div>
              <div class="legend-row"><div class="legend-dot" style="background:#e05252"></div><span class="legend-name">Dana Darurat</span><span class="legend-pct">8%</span></div>
            </div>
          </div>
        </div>
      </div>

      <div class="three-col">
        <div class="panel" style="padding:18px">
          <div class="panel-hd">
            <span class="panel-title">Mutasi Terkini</span>
            <button class="panel-link">Semua</button>
          </div>
          <div class="tx-list">
            <div class="tx-item">
              <div class="tx-icon" style="background:#eaf7ee;font-size:17px">🛒</div>
              <div class="tx-info">
                <div class="tx-name">Belanja Supermarket</div>
                <div class="tx-date">Hari ini, 09:34</div>
              </div>
              <div class="tx-amt out">-Rp 187rb</div>
            </div>
            <div class="tx-item">
              <div class="tx-icon" style="background:#eaf7ee;font-size:17px">💼</div>
              <div class="tx-info">
                <div class="tx-name">Gaji April</div>
                <div class="tx-date">17 Apr, 08:00</div>
              </div>
              <div class="tx-amt in">+Rp 8,2jt</div>
            </div>
            <div class="tx-item">
              <div class="tx-icon" style="background:#fff5e6;font-size:17px">🚌</div>
              <div class="tx-info">
                <div class="tx-name">Top-up Commuter</div>
                <div class="tx-date">17 Apr, 07:10</div>
              </div>
              <div class="tx-amt out">-Rp 100rb</div>
            </div>
            <div class="tx-item">
              <div class="tx-icon" style="background:#f0f5ff;font-size:17px">💡</div>
              <div class="tx-info">
                <div class="tx-name">Tagihan Listrik</div>
                <div class="tx-date">16 Apr, 14:22</div>
              </div>
              <div class="tx-amt out">-Rp 220rb</div>
            </div>
            <div class="tx-item">
              <div class="tx-icon" style="background:#eaf7ee;font-size:17px">📈</div>
              <div class="tx-info">
                <div class="tx-name">Dividen Reksa Dana</div>
                <div class="tx-date">15 Apr, 11:00</div>
              </div>
              <div class="tx-amt in">+Rp 315rb</div>
            </div>
          </div>
        </div>

        <div class="panel" style="padding:18px">
          <div class="panel-hd">
            <span class="panel-title">Rekomendasi Investasi</span>
          </div>
          <div class="invest-list">
            <div class="invest-item">
              <div class="invest-top">
                <span class="invest-name">Reksa Dana Pasar Uang</span>
                <span class="invest-tag tag-low">Rendah</span>
              </div>
              <div class="invest-desc">Cocok untuk pemula, return stabil 5-6% p.a.</div>
              <div class="invest-bar"><div class="invest-prog" style="width:60%;background:var(--g400)"></div></div>
              <div class="invest-meta"><span>Return: 5.8%</span><span>Modal min: Rp 10rb</span></div>
            </div>
            <div class="invest-item">
              <div class="invest-top">
                <span class="invest-name">Obligasi Negara (ORI)</span>
                <span class="invest-tag tag-low">Rendah</span>
              </div>
              <div class="invest-desc">Dijamin negara, bunga tetap 6,25% p.a.</div>
              <div class="invest-bar"><div class="invest-prog" style="width:72%;background:var(--g500)"></div></div>
              <div class="invest-meta"><span>Return: 6.25%</span><span>Modal min: Rp 1jt</span></div>
            </div>
            <div class="invest-item">
              <div class="invest-top">
                <span class="invest-name">Saham Blue-Chip IDX30</span>
                <span class="invest-tag tag-med">Menengah</span>
              </div>
              <div class="invest-desc">Portofolio saham unggulan, potensi 10-15%.</div>
              <div class="invest-bar"><div class="invest-prog" style="width:45%;background:#f0b429"></div></div>
              <div class="invest-meta"><span>Return: ~12%</span><span>Modal min: Rp 100rb</span></div>
            </div>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px">
          <div class="panel" style="padding:18px">
            <div class="panel-hd">
              <span class="panel-title">Smart Budgeting</span>
              <button class="panel-link">Edit</button>
            </div>
            <div class="budget-grid">
              <div class="bud-card">
                <div class="bud-cat">Makanan</div>
                <div class="bud-used">Rp 1,8jt</div>
                <div class="bud-total">dari Rp 2,5jt</div>
                <div class="bud-track"><div class="bud-prog prog-ok" style="width:72%"></div></div>
              </div>
              <div class="bud-card">
                <div class="bud-cat">Transport</div>
                <div class="bud-used">Rp 950rb</div>
                <div class="bud-total">dari Rp 1jt</div>
                <div class="bud-track"><div class="bud-prog prog-warn" style="width:95%"></div></div>
              </div>
              <div class="bud-card">
                <div class="bud-cat">Hiburan</div>
                <div class="bud-used">Rp 500rb</div>
                <div class="bud-total">dari Rp 400rb</div>
                <div class="bud-track"><div class="bud-prog prog-over" style="width:100%"></div></div>
              </div>
            </div>
          </div>

          <div class="panel" style="padding:18px">
            <div class="panel-hd">
              <span class="panel-title">Tips Keuangan</span>
            </div>
            <div class="tips-list">
              <div class="tip-card green">
                <div class="tip-head">Tabungan darurat: ✓ Aman</div>
                <div class="tip-body">Dana darurat kamu sudah mencapai 3x pengeluaran bulanan.</div>
              </div>
              <div class="tip-card amber">
                <div class="tip-head">Hiburan melebihi anggaran</div>
                <div class="tip-body">Kurangi Rp 100rb pengeluaran hiburan minggu ini.</div>
              </div>
              <div class="tip-card blue">
                <div class="tip-head">Peluang investasi baru</div>
                <div class="tip-body">Sisa saldo Rp 2,8jt bisa dioptimalkan di reksa dana.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="two-col">
        <div class="panel" style="padding:18px">
          <div class="panel-hd">
            <span class="panel-title">Literasi Finance</span>
            <button class="panel-link">Semua modul</button>
          </div>
          <div class="lite-list">
            <div class="lite-item">
              <div class="lite-icon">📊</div>
              <div class="lite-info">
                <div class="lite-title">Memahami Reksa Dana untuk Pemula</div>
                <div class="lite-sub">5 menit baca · Level: Dasar</div>
              </div>
              <span class="lite-arr">›</span>
            </div>
            <div class="lite-item">
              <div class="lite-icon">🏦</div>
              <div class="lite-info">
                <div class="lite-title">Cara Kerja Bunga Majemuk</div>
                <div class="lite-sub">8 menit baca · Level: Menengah</div>
              </div>
              <span class="lite-arr">›</span>
            </div>
            <div class="lite-item">
              <div class="lite-icon">🛡️</div>
              <div class="lite-info">
                <div class="lite-title">Strategi Dana Darurat yang Tepat</div>
                <div class="lite-sub">4 menit baca · Level: Dasar</div>
              </div>
              <span class="lite-arr">›</span>
            </div>
            <div class="lite-item">
              <div class="lite-icon">📈</div>
              <div class="lite-info">
                <div class="lite-title">Analisis Saham: P/E Ratio & Valuasi</div>
                <div class="lite-sub">12 menit baca · Level: Lanjut</div>
              </div>
              <span class="lite-arr">›</span>
            </div>
          </div>
        </div>
        <div class="panel" style="padding:18px">
          <div class="panel-hd">
            <span class="panel-title">Progres Tujuan Keuangan</span>
          </div>
          <div style="display:flex;flex-direction:column;gap:14px">
            <div>
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                <span style="font-size:13px;font-weight:600;color:var(--text1)">🏠 DP Rumah</span>
                <span style="font-size:12px;font-weight:700;color:var(--g600)">34%</span>
              </div>
              <div style="height:10px;background:var(--g50);border-radius:5px;overflow:hidden"><div style="height:100%;width:34%;background:var(--g400);border-radius:5px"></div></div>
              <div style="font-size:11px;color:var(--text3);margin-top:4px">Rp 34jt dari Rp 100jt · Target: Des 2027</div>
            </div>
            <div>
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                <span style="font-size:13px;font-weight:600;color:var(--text1)">✈️ Liburan Eropa</span>
                <span style="font-size:12px;font-weight:700;color:var(--g600)">61%</span>
              </div>
              <div style="height:10px;background:var(--g50);border-radius:5px;overflow:hidden"><div style="height:100%;width:61%;background:var(--g500);border-radius:5px"></div></div>
              <div style="font-size:11px;color:var(--text3);margin-top:4px">Rp 18,3jt dari Rp 30jt · Target: Jul 2026</div>
            </div>
            <div>
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                <span style="font-size:13px;font-weight:600;color:var(--text1)">🎓 Dana Pendidikan</span>
                <span style="font-size:12px;font-weight:700;color:var(--g600)">18%</span>
              </div>
              <div style="height:10px;background:var(--g50);border-radius:5px;overflow:hidden"><div style="height:100%;width:18%;background:var(--g600);border-radius:5px"></div></div>
              <div style="font-size:11px;color:var(--text3);margin-top:4px">Rp 9jt dari Rp 50jt · Target: Jan 2030</div>
            </div>
            <div style="margin-top:4px;padding:12px;background:var(--g50);border-radius:10px;border:1px dashed var(--g200);text-align:center;cursor:pointer">
              <span style="font-size:13px;font-weight:600;color:var(--g600)">+ Tambah Tujuan Baru</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
