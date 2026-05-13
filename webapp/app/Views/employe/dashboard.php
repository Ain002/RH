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
<section id="page-dashboard-employe">
<div class="app-wrap">

  <!-- SIDEBAR EMPLOYÉ -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <ul class="sidebar-nav">
      <li><a href="<?= base_url('/employe/dashboard') ?>" class="active"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= base_url('/employe/create') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="<?= base_url('/employe/list') ?>">
          <i class="bi bi-calendar3"></i> Mes demandes
          <span class="nav-badge alert"><?= $stats['en_attente'] ?? 0 ?></span>
        </a>
      </li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green"><?= strtoupper(substr($user['nom'] ?? 'U', 0, 1) . substr($user['prenom'] ?? 'S', 0, 1)) ?></div>
        <div>
          <div class="user-name"><?= esc($user['prenom'] ?? '') ?> <?= esc($user['nom'] ?? '') ?></div>
          <div class="user-role">Employé · <?= esc($user['departement_id'] ?? 'IT') ?></div>
        </div>
        <a href="<?= base_url('/auth/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Tableau de bord</div>
        <div class="topbar-breadcrumb">Accueil</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= base_url('/employe/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
          <i class="bi bi-plus-lg"></i> Nouvelle demande
        </a>
      </div>
    </div>

    <div class="content">

      <!-- Métriques -->
      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val"><?= $stats['en_attente'] ?? 0 ?></div>
          <div class="metric-label">En attente</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
          <div class="metric-val"><?= $stats['approuvees'] ?? 0 ?></div>
          <div class="metric-label">Approuvées</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val"><?php 
            $firstSolde = $soldes[0] ?? null;
            if ($firstSolde) {
              echo ($firstSolde['jours_attribues'] - $firstSolde['jours_pris']);
            } else {
              echo 'N/A';
            }
          ?></div>
          <div class="metric-label">Jours restants</div>
          <div class="metric-sub">sur <?= $firstSolde['jours_attribues'] ?? 30 ?> cette année</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
          <div class="metric-val"><?= $stats['refusees'] ?? 0 ?></div>
          <div class="metric-label">Refusée</div>
        </div>
      </div>

      <!-- Soldes de congés -->
      <div class="data-card">
        <div class="data-card-head"><h3>Mes soldes de congés — <?= date('Y') ?></h3></div>
        <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
          <?php if (!empty($soldes)): ?>
            <?php foreach ($soldes as $solde): ?>
              <?php 
                $jAttribues = $solde['jours_attribues'] ?? 0;
                $jPris = $solde['jours_pris'] ?? 0;
                $jRestants = $jAttribues - $jPris;
                $pourcentage = $jAttribues > 0 ? ($jRestants / $jAttribues * 100) : 0;
                $cssClass = $pourcentage < 25 ? 'warn' : '';
              ?>
              <div class="solde-card" style="margin:0">
                <div class="solde-header">
                  <span class="solde-type"><?= esc($solde['type_libelle'] ?? 'Congé') ?></span>
                  <span class="solde-nums"><strong><?= $jRestants ?></strong> / <?= $jAttribues ?> j</span>
                </div>
                <div class="solde-bar"><div class="solde-fill <?= $cssClass ?>" style="width:<?= $pourcentage ?>%"></div></div>
                <div class="solde-label"><?= $jRestants ?> jours restants · <?= $jPris ?> pris</div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color:var(--muted)">Aucune donnée de solde disponible.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Dernières demandes -->
      <div class="data-card">
        <div class="data-card-head">
          <h3>Mes dernières demandes</h3>
          <a href="<?= base_url('/employe/list') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
        </div>
        <?php if (!empty($demandes)): ?>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Du</th><th>Au</th><th>Durée</th><th>Statut</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php foreach ($demandes as $demande): ?>
              <?php 
                $statut = $demande['statut'] ?? 'en_attente';
                $statusClass = match($statut) {
                  'approuvee' => 's-approuvee',
                  'refusee' => 's-refusee',
                  default => 's-attente'
                };
                $typeClass = match(strtolower($demande['type_libelle'] ?? 'annuel')) {
                  'maladie' => 't-maladie',
                  'spécial' => 't-special',
                  default => 't-annuel'
                };
              ?>
              <tr>
                <td><span class="type-badge <?= $typeClass ?>"><?= esc($demande['type_libelle'] ?? 'Annuel') ?></span></td>
                <td class="td-muted"><?= date('d M Y', strtotime($demande['date_debut'])) ?></td>
                <td class="td-muted"><?= date('d M Y', strtotime($demande['date_fin'])) ?></td>
                <td class="td-mono"><?= $demande['nb_jours'] ?? 0 ?> j</td>
                <td><span class="statut <?= $statusClass ?>"><?= ucfirst($statut) ?></span></td>
                <td>
                  <?php if ($statut === 'en_attente'): ?>
                    <button class="btn-sm btn-cancel"><i class="bi bi-x"></i> Annuler</button>
                  <?php else: ?>
                    <span class="td-muted" style="font-size:.75rem">—</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p style="padding:1rem;color:var(--muted)">Aucune demande enregistrée.</p>
        <?php endif; ?>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Projet CodeIgniter 4</div>
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
