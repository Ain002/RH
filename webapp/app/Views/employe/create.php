<?php 

$typesConge = (new \App\Functions\TypeCongerFunction())->getAllTypesConge();

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
<section id="page-form-conge">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <ul class="sidebar-nav" style="margin-top:1rem">
      <li><a href="<?= base_url('/employe/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= base_url('/employe/create') ?>" class="active"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="<?= base_url('/employe/list') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green"><?= strtoupper(substr(session()->get('user')['nom'] ?? 'U', 0, 1)) ?></div>
        <div><div class="user-name"><?= esc(session()->get('nom') ?? 'Employé') ?></div><div class="user-role">Employé · IT</div></div>
        <a href="<?= base_url('/auth/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Nouvelle demande de congé</div>
        <div class="topbar-breadcrumb">
          <a href="<?= base_url('/employe/dashboard') ?>">Accueil</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande
        </div>
      </div>
    </div>

    <div class="content">

      <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start" class="form-layout">

        <!-- Formulaire principal -->
         <form action="<?= base_url('/employe/soumettre') ?>" method="post" class="data-card">
        <div>
          <div class="form-section">
            <h3>Détails de la demande</h3>

            <div class="f-group" style="margin-bottom:1rem">
              <label class="f-label">Type de congé <span style="color:var(--danger)">*</span></label>
              <select class="f-select" name="type_conge_id">
                <option value="">-- Choisir un type --</option>
                <?php foreach ($typesConge as $type): ?>
                  <option value="<?= esc($type['id'] ?? $type->id ?? '') ?>"><?= esc($type['libelle'] ?? $type->nom ?? '') ?></option>
                <?php endforeach; ?>
              </select>
              <!-- Erreur validation CI4 -->
              <div class="f-error"><i class="bi bi-exclamation-circle"></i> Ce champ est requis.</div>
            </div>

            <div class="form-grid-2" style="margin-bottom:1rem">
              <div class="f-group">
                <label class="f-label">Date de début <span style="color:var(--danger)">*</span></label>
                <input type="date" class="f-input" name="date_debut" id="date_debut"/>
              </div>
              <div class="f-group">
                <label class="f-label">Date de fin <span style="color:var(--danger)">*</span></label>
                <input type="date" class="f-input" name="date_fin" id="date_fin"/>
              </div>
            </div>

            <!-- Calcul automatique côté JS -->
            <div class="f-computed">
              <div class="f-computed-num" id="jours_calcules">0</div>
              <div class="f-computed-label">jours calendaires calculés<br><span style="font-size:.7rem;opacity:.7" id="periode_affichage"></span></div>
            </div>

            <div class="f-group" style="margin-bottom:1rem">
              <label class="f-label">Motif (optionnel)</label>
              <textarea class="f-textarea" name="commentaire" placeholder="Précisez le motif de votre demande si nécessaire..."></textarea>
              <div class="f-hint">Le motif est visible par le responsable RH.</div>
            </div>

            <div class="form-actions">
              <button class="btn-forest" type="submit"><i class="bi bi-send"></i> Soumettre la demande</button>
              <a href="<?= base_url('/employe/dashboard') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
            </div>
          </div>
        </div>
        </form>

        <!-- Panneau latéral : solde & règles -->
        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
              <?php if (!empty($soldes)): ?>
                <?php foreach ($soldes as $solde): ?>
                  <?php 
                    $jAttribues = $solde['jours_attribues'] ?? 0;
                    $jPris = $solde['jours_pris'] ?? 0;
                    $jRestants = $jAttribues - $jPris;
                    $pourcentage = $jAttribues > 0 ? ($jRestants / $jAttribues * 100) : 0;
                    $cssClass = $pourcentage < 25 ? 'warn' : '';
                  ?>
                  <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                      <span style="font-size:.8rem;color:var(--ink)"><?= esc($solde['type_libelle'] ?? 'Congé') ?></span>
                      <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500"><?= $jRestants ?> j</span>
                    </div>
                    <div class="solde-bar"><div class="solde-fill <?= $cssClass ?>" style="width:<?= $pourcentage ?>%"></div></div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p style="color:var(--muted);font-size:.85rem">Aucune donnée de solde disponible.</p>
              <?php endif; ?>
          </div>
          <div class="flash flash-info" style="margin:0">
            <i class="bi bi-info-circle-fill"></i>
            <span style="font-size:.8rem">Le solde est déduit uniquement à l'approbation de votre responsable.</span>
          </div>
          <div style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:.85rem 1rem">
            <div style="font-size:.78rem;font-weight:500;color:var(--ink);margin-bottom:.5rem"><i class="bi bi-clipboard-check" style="color:var(--forest);margin-right:5px"></i>Rappel des règles</div>
            <ul style="margin:0;padding-left:1rem;font-size:.75rem;color:var(--muted);line-height:1.7">
              <li>Préavis minimum : 48h avant la date de début</li>
              <li>Pas de chevauchement avec une demande en cours</li>
              <li>Solde insuffisant = demande refusée automatiquement</li>
            </ul>
          </div>
        </div>

      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</section>

<!-- Calcul dynamique des jours -->
<script>
function calculerJours() {
  const debut = document.getElementById('date_debut').value;
  const fin = document.getElementById('date_fin').value;
  
  if (!debut || !fin) return;
  
  const dateDebut = new Date(debut + 'T00:00:00');
  const dateFin = new Date(fin + 'T00:00:00');
  
  if (dateDebut > dateFin) return;
  
  const jours = Math.floor((dateFin - dateDebut) / (1000 * 60 * 60 * 24)) + 1;
  
  document.getElementById('jours_calcules').textContent = jours;
  
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  const debutStr = dateDebut.toLocaleDateString('fr-FR', options);
  const finStr = dateFin.toLocaleDateString('fr-FR', options);
  
  document.getElementById('periode_affichage').textContent = `du ${debutStr} au ${finStr}`;
}

document.getElementById('date_debut').addEventListener('change', calculerJours);
document.getElementById('date_fin').addEventListener('change', calculerJours);
</script>
</body>
</html>
