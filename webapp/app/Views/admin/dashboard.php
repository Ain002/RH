<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>TechMada RH — Gestion des congés CI4</title>
<link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet"/>
<link href="<?= base_url('assets/bootstrap/css/bootstrap-icons.min.css') ?>" rel="stylesheet"/>
<link href="<?= base_url('assets/css/css2.css') ?>" rel="stylesheet"/>
<link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet"/>
</head>
<body>
<section id="page-dashboard-admin">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div>
      <div class="sidebar-brand-name">TechMada RH
        <span>Administration</span>
      </div>
    </div>
    <div class="sidebar-section">Gestion</div>
    <ul class="sidebar-nav">
      <li><a href="#" class="active"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
      <li><a href="<?= base_url('/admin') ?>"><i class="bi bi-people"></i> Employés</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar" style="background:#5a2d82;width:32px;height:32px;font-size:.7rem">AD</div>
        <div><div class="user-name">Administrateur</div><div class="user-role">Admin système</div></div>
        <a href="<?= base_url('/auth/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Vue d'ensemble</div>
        <div class="topbar-breadcrumb">Administration</div>
      </div>
      <div class="topbar-actions">
        <a href="#page-admin-employes" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter un employé</a>
      </div>
    </div>

    <div class="content">

      <!-- Métriques admin -->
      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div>
          <div class="metric-val"><?= $totalEmployes ?></div>
          <div class="metric-label">Employés actifs</div>
          <div class="metric-sub up"><i class="bi bi-arrow-up-short"></i> +<?= min(rand(1, 5), $totalEmployes) ?> ce mois</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val"><?= $attentes ?></div>
          <div class="metric-label">Demandes en attente</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val"><?= $approuvees ?></div>
          <div class="metric-label">Approuvées ce mois</div>
          <div class="metric-sub up"><i class="bi bi-arrow-up-short"></i> +<?= rand(1, 10) ?> vs mois dernier</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div>
          <div class="metric-val"><?= $departements ?></div>
          <div class="metric-label">Départements</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-person-slash"></i></div></div>
          <div class="metric-val"><?= count($absents) ?></div>
          <div class="metric-label">Absents aujourd'hui</div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">

        <!-- Demandes récentes -->
        <div class="data-card" style="margin:0">
          <div class="data-card-head">
            <h3>Demandes récentes</h3>
            <a href="#page-liste-rh" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
          </div>
          <table class="tbl">
            <thead>
              <tr><th>Employé</th><th>Type</th><th>Durée</th><th>Statut</th></tr>
            </thead>
            <tbody>
              <?php if (!empty($conges_recentes)): ?>
                <?php foreach ($conges_recentes as $conge): 
                  $debut = new DateTime($conge['date_debut']);
                  $fin = new DateTime($conge['date_fin']);
                  $interval = $debut->diff($fin);
                ?>
                <tr>
                  <td><div style="display:flex;align-items:center;gap:7px"><div class="avatar av-green" style="width:28px;height:28px;font-size:.62rem"><?= strtoupper(substr($conge['employe_nom'], 0, 1) . substr($conge['employe_prenom'], 0, 1)) ?></div><span class="td-name" style="font-size:.84rem"><?= $conge['employe_nom'] ?> <?= $conge['employe_prenom'] ?></span></div></td>
                  <td><span class="type-badge t-annuel"><?= $conge['type_conge_libelle'] ?></span></td>
                  <td class="td-mono"><?= $interval->format('%a') ?> j</td>
                  <td><span class="statut s-<?= $conge['statut'] ?>"><?= $conge['statut'] ?></span></td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" style="text-align:center;padding:1rem;color:var(--muted)">Aucune demande</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Absents du jour + soldes critiques -->
        <div style="display:flex;flex-direction:column;gap:1rem">
          <!-- Absents du jour -->
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3><i class="bi bi-person-slash" style="color:var(--muted);margin-right:5px"></i>Absents aujourd'hui</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.6rem">
              <?php if (!empty($absents)): ?>
                <?php foreach ($absents as $absent): ?>
                <div style="display:flex;align-items:center;gap:8px">
                  <div class="avatar av-green" style="width:30px;height:30px;font-size:.65rem"><?= strtoupper(substr($absent['nom'], 0, 1) . substr($absent['prenom'], 0, 1)) ?></div>
                  <div><div style="font-size:.83rem;font-weight:500;color:var(--ink)"><?= $absent['nom'] ?> <?= $absent['prenom'] ?></div><div style="font-size:.72rem;color:var(--muted)"><?= $absent['type_conge'] ?> · retour <?= date('d/m', strtotime($absent['date_fin'])) ?></div></div>
                </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div style="font-size:.82rem;color:var(--muted);padding:.5rem 0">Aucun absent aujourd'hui</div>
              <?php endif; ?>
            </div>
          </div>
          <div class="flash flash-warn" style="margin:0">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span style="font-size:.8rem">2 employés ont un solde critique (≤ 2 jours). <a href="#" style="color:var(--warn);font-weight:500">Voir les soldes →</a></span>
          </div>
        </div>

      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</section>

<!-- Navigation demo interne -->
<script>
document.querySelectorAll('a[href^="#"]').forEach(a=>{
  a.addEventListener('click',e=>{
    const t=document.querySelector(a.getAttribute('href'));
    if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'})}
  });
});
</script>
</body>
</html>
