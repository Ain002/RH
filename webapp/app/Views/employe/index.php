<?php
$demande = $demande ?? null;
?>

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
<section id="page-mes-conges">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <ul class="sidebar-nav" style="margin-top:1rem">
        <li><a href="<?= base_url('/employe/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= base_url('/employe/create') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="<?= base_url('/employe/list') ?>" class="active"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green"><?= strtoupper(substr(session()->get('user')['nom'] ?? 'U', 0, 1) . substr(session()->get('user')['prenom'] ?? 'S', 0, 1)) ?></div>
        <div><div class="user-name"><?= esc(session()->get('prenom') ?? 'Employé') ?> <?= esc(session()->get('user')['nom'] ?? '') ?></div><div class="user-role">Employé · <?= esc(session()->get('user')['departement_id'] ?? 'IT') ?></div></div>
        <a href="<?= base_url('/auth/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Mes demandes de congé</div>
        <div class="topbar-breadcrumb"><a href="<?= base_url('/employe/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mes demandes</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= base_url('/employe/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
      </div>
    </div>

    <div class="content">
      <div class="data-card">
        <div class="data-card-head">
          <h3>Toutes mes demandes</h3>
          <div style="display:flex;gap:6px">
            <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
              <option>Tous les statuts</option>
              <option>En attente</option>
              <option>Approuvée</option>
              <option>Refusée</option>
              <option>Annulée</option>
            </select>
          </div>
        </div>
        <?php if (!empty($demandes)): ?>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php foreach ($demandes as $demande): ?>
              <?php 
                $statut = $demande['statut'] ?? 'en_attente';
                $statusClass = match($statut) {
                  'approuvee' => 's-approuvee',
                  'refusee' => 's-refusee',
                  'annulee' => 's-annulee',
                  default => 's-attente'
                };
                $typeClass = match(strtolower($demande['type_libelle'] ?? 'annuel')) {
                  'maladie' => 't-maladie',
                  'spécial' => 't-special',
                  'sans solde' => 't-sans-solde',
                  default => 't-annuel'
                };
              ?>
              <tr>
                <td><span class="type-badge <?= $typeClass ?>"><?= esc($demande['type_libelle'] ?? 'Annuel') ?></span></td>
                <td class="td-muted"><?= date('d M Y', strtotime($demande['date_debut'])) ?></td>
                <td class="td-muted"><?= date('d M Y', strtotime($demande['date_fin'])) ?></td>
                <td class="td-mono"><?= $demande['nb_jours'] ?? 0 ?> j</td>
                <td><span class="statut <?= $statusClass ?>"><?= ucfirst($statut) ?></span></td>
                <td class="td-muted" style="font-size:.78rem"><?= esc($demande['commentaire_rh'] ?? '—') ?></td>
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
          <div style="padding:2rem;text-align:center;color:var(--muted)">
            <p><i class="bi bi-inbox" style="font-size:2rem;margin-bottom:1rem;display:block"></i></p>
            <p>Aucune demande enregistrée.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</section>

</body>
</html>
