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
<section id="page-liste-rh">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-person-check"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li>
        <a href="#" class="active">
          Demandes à traiter
          <?php if (count($attentes) > 0) { ?>
          <span class="nav-badge alert"><?= count($attentes) ?></span>
          <?php } ?>
        </a>
      </li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-blue">MR</div>
        <div><div class="user-name">Marie Rabe</div><div class="user-role">Responsable RH</div></div>
        <a href="<?= base_url('/auth/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Demandes à traiter</div>
        <div class="topbar-breadcrumb"><a href="#page-dashboard-rh">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div>
      </div>
      <div class="topbar-actions">
        <span style="font-size:.8rem;color:var(--muted);background:var(--warn-bg);border:1px solid var(--warn-br);border-radius:6px;padding:5px 10px;display:flex;align-items:center;gap:5px;color:var(--warn)">
          <i class="bi bi-hourglass-split"></i> 4 en attente
        </span>
      </div>
    </div>

    <div class="content">

      <!-- Flash -->
      <div class="flash flash-success" id="flash-message" style="display:none;">
        <i class="bi bi-check-circle-fill"></i>
        <span id="flash-text"></span>
      </div>

      <!-- Filtre -->
      <div style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap">
        <a href="<?= base_url('rh?option=0') ?>"><button class="<?php if ($option == 0) echo 'selection'; else echo 'option' ?>">Tous (<?= count($all) ?>)</button></a>
        <a href="<?= base_url('rh?option=1') ?>"><button class="<?php if ($option == 1) echo 'selection'; else echo 'option' ?>">En attente (<?= count($attentes) ?>)</button></a>
        <a href="<?= base_url('rh?option=2') ?>"><button class="<?php if ($option == 2) echo 'selection'; else echo 'option' ?>">Approuvées (<?= count($approuvees) ?>)</button></a>
        <a href="<?= base_url('rh?option=3') ?>"><button class="<?php if ($option == 3) echo 'selection'; else echo 'option' ?>">Refusées (<?= count($refusees) ?>)</button></a>
        <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto;margin-left:auto">
          <option>Tous les départements</option>
          <option>IT</option>
          <option>Finance</option>
          <option>Marketing</option>
        </select>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Toutes les demandes</h3></div>
        <?php 
          $data = [];
          if ($option == 0) {
            $data = $all;
          } else if ($option == 1) {
            $data = $attentes;
          } else if ($option == 2) {
            $data = $approuvees;
          } else {
            $data = $refusees;
          }
          if (empty($data)) { 
        ?>
        <div class="empty"><p>Aucune demande trouvée</p></div>
        <?php } else { ?>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Type</th><th>Période</th><th>Durée</th><th>Solde dispo</th><th>Statut</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($data as $dat) { ?>
            <tr>
              <td>
                <div class="profile-row">
                  <div class="avatar av-green" style="width:32px;height:32px;font-size:.7rem"><?= $dat['employe_id'] ?></div>
                  <div class="profile-info">
                    <div class="pname"><?= $dat['employe_nom'] ?> <?= $dat['employe_prenom'] ?></div>
                  </div>
                </div>
              </td>
              <td><span class="type-badge t-annuel"><?= $dat['type_conge_libelle'] ?></span></td>
              <td class="td-muted" style="font-size:.8rem"><?= $dat['date_debut'] ?> – <?= $dat['date_fin'] ?></td>
              <td class="td-mono">
                <?php
                  $debut = new DateTime($dat['date_debut']);
                  $fin = new DateTime($dat['date_fin']);

                  $interval = $debut->diff($fin);

                  echo ($interval->format('%a') + 1) . ' j';
                  ?>
              </td>
              <td>
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--success);font-weight:500">
                  <?= isset($dat['jours_attribues']) && isset($dat['jours_pris']) ? ($dat['jours_attribues'] - $dat['jours_pris']) : 0 ?> j
                </span>
                <span style="font-size:.72rem;color:var(--muted)"> dispo</span>
              </td>
              <td><span class="statut s-<?= $dat['statut'] ?>"><?= $dat['statut'] ?></span></td>
              <td>
                <?php 
                  $joursAttribues = isset($dat['jours_attribues']) ? $dat['jours_attribues'] : 0;
                  $joursPris = isset($dat['jours_pris']) ? $dat['jours_pris'] : 0;
                  $joursDisponibles = $joursAttribues - $joursPris;
                  $soldesInsuffisants = $dat['nb_jours'] > $joursDisponibles;
                  
                  if ($dat['statut'] == 'en_attente') { 
                    if ($soldesInsuffisants) {
                ?>
                <div style="font-size:.8rem;color:var(--danger);font-weight:500">
                  <i class="bi bi-exclamation-triangle"></i> Soldes insuffisants
                </div>
                <?php } else { ?>
                <div class="action-btns">
                  <button class="btn-sm btn-approve" onclick="openConfirmModal(this, 'approve')" data-conge-id="<?= $dat['id'] ?>" data-employe-nom="<?= htmlspecialchars($dat['employe_nom'] . ' ' . $dat['employe_prenom']) ?>" data-nb-jours="<?= $dat['nb_jours'] ?>" data-type-conge="<?= htmlspecialchars($dat['type_conge_libelle']) ?>" data-date-debut="<?= $dat['date_debut'] ?>" data-date-fin="<?= $dat['date_fin'] ?>"><i class="bi bi-check-lg"></i> Approuver</button>
                  <button class="btn-sm btn-refuse" onclick="openConfirmModal(this, 'refuse')" data-conge-id="<?= $dat['id'] ?>" data-employe-nom="<?= htmlspecialchars($dat['employe_nom'] . ' ' . $dat['employe_prenom']) ?>" data-nb-jours="<?= $dat['nb_jours'] ?>" data-type-conge="<?= htmlspecialchars($dat['type_conge_libelle']) ?>" data-date-debut="<?= $dat['date_debut'] ?>" data-date-fin="<?= $dat['date_fin'] ?>" data-jours-dispo="<?= $joursDisponibles ?>"><i class="bi bi-x-lg"></i> Refuser</button>
                </div>
                <?php }
                  } ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php } ?>
      </div>

      <!-- Modal refus (dynamique, caché par défaut) -->
      <div id="confirm-modal" style="display:none;margin-top:1.5rem">
        <div class="form-section" id="refuse-section" style="border-color:var(--danger-br);background:var(--danger-bg);display:none">
          <h3 style="color:var(--danger)"><i class="bi bi-x-circle"></i> Confirmer le refus — <span id="modal-employe-nom"></span></h3>
          <div style="font-size:.875rem;color:var(--ink);margin-bottom:1rem">
            Demande de <strong><span id="modal-nb-jours"></span> jours</strong> du <span id="modal-date-debut"></span> au <span id="modal-date-fin"></span> · Type : <span id="modal-type-conge"></span><br>
            <span id="solde-warning" style="font-size:.8rem;color:var(--danger);display:none"><i class="bi bi-exclamation-triangle"></i> Solde insuffisant : <span id="modal-jours-dispo"></span> jour disponible, <span id="modal-nb-jours2"></span> demandés.</span>
          </div>
          <form method="POST" action="<?= base_url('/rh/refuser') ?>">
            <input type="hidden" name="conge_id" id="modal-conge-id">
            <div class="f-group">
              <label class="f-label">Commentaire pour l'employé (optionnel)</label>
              <textarea class="f-textarea" name="commentaire" placeholder="Ex : Solde insuffisant, veuillez contacter les RH pour un congé sans solde."></textarea>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn-sm btn-refuse" style="padding:9px 16px;font-size:.875rem"><i class="bi bi-x-lg"></i> Confirmer le refus</button>
              <button type="button" class="btn-secondary" onclick="closeConfirmModal()"><i class="bi bi-arrow-left"></i> Annuler</button>
            </div>
          </form>
        </div>

        <div class="form-section" id="approve-section" style="border-color:var(--success-br);background:var(--success-bg);display:none">
          <h3 style="color:var(--success)"><i class="bi bi-check-circle"></i> Confirmer l'approbation — <span id="modal-employe-nom2"></span></h3>
          <div style="font-size:.875rem;color:var(--ink);margin-bottom:1rem">
            Demande de <strong><span id="modal-nb-jours3"></span> jours</strong> du <span id="modal-date-debut2"></span> au <span id="modal-date-fin2"></span> · Type : <span id="modal-type-conge2"></span><br>
            <span style="font-size:.8rem;color:var(--success)"><i class="bi bi-info-circle"></i> Les jours seront déduits du solde de l'employé.</span>
          </div>
          <form method="POST" action="<?= base_url('/rh/approuver') ?>">
            <input type="hidden" name="conge_id" id="modal-conge-id2">
            <div class="form-actions">
              <button type="submit" class="btn-sm btn-approve" style="padding:9px 16px;font-size:.875rem"><i class="bi bi-check-lg"></i> Confirmer l'approbation</button>
              <button type="button" class="btn-secondary" onclick="closeConfirmModal()"><i class="bi bi-arrow-left"></i> Annuler</button>
            </div>
          </form>
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

// Gestion des modales de confirmation
function openConfirmModal(button, action) {
  const modal = document.getElementById('confirm-modal');
  const congeId = button.getAttribute('data-conge-id');
  const employeNom = button.getAttribute('data-employe-nom');
  const nbJours = button.getAttribute('data-nb-jours');
  const typeConge = button.getAttribute('data-type-conge');
  const dateDebut = button.getAttribute('data-date-debut');
  const dateFin = button.getAttribute('data-date-fin');
  const joursDispo = button.getAttribute('data-jours-dispo');

  if (action === 'refuse') {
    // Remplir les champs pour la modale de refus
    document.getElementById('refuse-section').style.display = 'block';
    document.getElementById('approve-section').style.display = 'none';
    document.getElementById('modal-employe-nom').innerText = employeNom;
    document.getElementById('modal-nb-jours').innerText = nbJours;
    document.getElementById('modal-date-debut').innerText = dateDebut;
    document.getElementById('modal-date-fin').innerText = dateFin;
    document.getElementById('modal-type-conge').innerText = typeConge;
    document.getElementById('modal-conge-id').value = congeId;
    document.getElementById('modal-nb-jours2').innerText = nbJours;
    document.getElementById('modal-jours-dispo').innerText = joursDispo;
  } else if (action === 'approve') {
    // Remplir les champs pour la modale d'approbation
    document.getElementById('approve-section').style.display = 'block';
    document.getElementById('refuse-section').style.display = 'none';
    document.getElementById('modal-employe-nom2').innerText = employeNom;
    document.getElementById('modal-nb-jours3').innerText = nbJours;
    document.getElementById('modal-date-debut2').innerText = dateDebut;
    document.getElementById('modal-date-fin2').innerText = dateFin;
    document.getElementById('modal-type-conge2').innerText = typeConge;
    document.getElementById('modal-conge-id2').value = congeId;
  }

  modal.style.display = 'block';
  modal.scrollIntoView({behavior: 'smooth', block: 'start'});
}

function closeConfirmModal() {
  const modal = document.getElementById('confirm-modal');
  modal.style.display = 'none';
}

// Afficher le message flash s'il existe
<?php if (session()->has('success')): ?>
  const flashMessage = document.getElementById('flash-message');
  const flashText = document.getElementById('flash-text');
  flashText.innerText = '<?= session()->getFlashdata("success") ?>';
  flashMessage.style.display = 'flex';
  setTimeout(() => {
    flashMessage.style.display = 'none';
  }, 5000);
<?php endif; ?>

<?php if (session()->has('error')): ?>
  // Afficher message d'erreur
  alert('<?= session()->getFlashdata("error") ?>');
<?php endif; ?>
</script>
</body>
</html>
