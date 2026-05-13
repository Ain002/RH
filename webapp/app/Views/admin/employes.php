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
<section id="page-admin-employes">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Administration</span></div>
    </div>
    <ul class="sidebar-nav" style="margin-top:1rem">
      <li><a href="<?= base_url('/admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
      <li><a href="#" class="active"><i class="bi bi-people"></i> Employés</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar" style="background:#5a2d82;width:32px;height:32px;font-size:.7rem">AD</div>
        <div><div class="user-name">Administrateur</div><div class="user-role">Admin système</div></div>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Gestion des employés</div>
        <div class="topbar-breadcrumb"><a href="#page-dashboard-admin">Admin</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Employés</div>
      </div>
      <div class="topbar-actions">
        <a href="#" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter</a>
      </div>
    </div>

    <div class="content">

      <!-- Formulaire ajout -->
      <div class="form-section">
        <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i>Ajouter un employé</h3>
        <form id="form-add-employe">
          <div class="form-grid-2" style="margin-bottom:1rem">
            <div class="f-group">
              <label class="f-label">Prénom</label>
              <input type="text" name="prenom" class="f-input" placeholder="Jean" required/>
            </div>
            <div class="f-group">
              <label class="f-label">Nom</label>
              <input type="text" name="nom" class="f-input" placeholder="Rakoto" required/>
            </div>
            <div class="f-group">
              <label class="f-label">Email</label>
              <input type="email" name="email" class="f-input" placeholder="jean.rakoto@techmada.mg" required/>
            </div>
            <div class="f-group">
              <label class="f-label">Mot de passe initial</label>
              <input type="password" name="password" class="f-input" placeholder="À communiquer à l'employé" required/>
            </div>
            <div class="f-group">
              <label class="f-label">Département</label>
              <select name="departement_id" class="f-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($departements as $dept): ?>
                <option value="<?= $dept['id'] ?>"><?= $dept['nom'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">Rôle</label>
              <select name="role" class="f-select" required>
                <option value="employe">Employé</option>
                <option value="rh">Responsable RH</option>
                <option value="admin">Administrateur</option>
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">Date d'embauche</label>
              <input type="date" name="date_embauche" class="f-input" value="<?= date('Y-m-d') ?>" required/>
            </div>
          </div>
          <div class="flash flash-info" style="margin-bottom:1rem">
            <i class="bi bi-info-circle-fill"></i>
            <span style="font-size:.82rem">Les soldes de congés seront initialisés automatiquement selon les types de congé configurés.</span>
          </div>
          <div id="form-message" style="display:none;margin-bottom:1rem" class="flash"></div>
          <div class="form-actions">
            <button type="submit" class="btn-forest"><i class="bi bi-plus"></i> Créer l'employé</button>
            <button type="reset" class="btn-secondary">Réinitialiser</button>
          </div>
        </form>
      </div>

      <!-- Liste employés -->
      <div class="data-card">
        <div class="data-card-head">
          <h3>Tous les employés</h3>
          <div style="display:flex;gap:6px">
            <input type="text" id="search-input" class="f-input" placeholder="Rechercher..." style="width:200px;padding:6px 10px;font-size:.8rem"/>
            <select id="filter-dept" class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
              <option value="">Tous les depts</option>
              <?php foreach ($departements as $dept): ?>
              <option value="<?= $dept['id'] ?>"><?= $dept['nom'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Département</th><th>Rôle</th><th>Embauche</th><th>Statut</th><th>Solde annuel</th><th>Actions</th></tr>
          </thead>
          <tbody id="employes-tbody">
            <?php if (!empty($employes)): ?>
              <?php foreach ($employes as $emp): ?>
              <tr class="employe-row" data-dept="<?= $emp['departement_id'] ?>" data-search="<?= strtolower($emp['nom'] . ' ' . $emp['prenom'] . ' ' . $emp['email']) ?>">
                <td>
                  <div class="profile-row">
                    <div class="avatar av-green" style="width:32px;height:32px;font-size:.68rem"><?= strtoupper(substr($emp['nom'], 0, 1) . substr($emp['prenom'], 0, 1)) ?></div>
                    <div class="profile-info"><div class="pname"><?= $emp['nom'] ?> <?= $emp['prenom'] ?></div><div class="pdept"><?= $emp['email'] ?></div></div>
                  </div>
                </td>
                <td class="td-muted"><?= $emp['departement_nom'] ?></td>
                <td><span class="type-badge" style="background:#f1efe8;color:#444441"><?= $emp['role'] ?></span></td>
                <td class="td-muted td-mono" style="font-size:.78rem"><?= $emp['date_embauche'] ?></td>
                <td><span class="statut s-<?= $emp['actif'] ? 'approuvee' : 'annulee' ?>" style="font-size:.68rem"><?= $emp['actif'] ? 'actif' : 'inactif' ?></span></td>
                <td><span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--forest)"><?= ($emp['solde_annuel_attribue'] - $emp['solde_annuel_pris']) ?> / <?= $emp['solde_annuel_attribue'] ?> j</span></td>
                <td>
                  <div class="action-btns">
                    <button class="btn-sm btn-edit"><i class="bi bi-pencil"></i> Éditer</button>
                    <button class="btn-sm btn-del"><i class="bi bi-slash-circle"></i></button>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="7" style="text-align:center;padding:1rem;color:var(--muted)">Aucun employé</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
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

// Gestion du formulaire d'ajout d'employé
const formAddEmploye = document.getElementById('form-add-employe');
formAddEmploye.addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const formData = new FormData(formAddEmploye);
  const messageDiv = document.getElementById('form-message');
  
  try {
    const response = await fetch('<?= base_url('/admin/add-employe') ?>', {
      method: 'POST',
      body: formData
    });
    
    const result = await response.json();
    
    messageDiv.style.display = 'block';
    if (result.success) {
      messageDiv.className = 'flash flash-success';
      messageDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + result.message;
      formAddEmploye.reset();
      // Recharger la page après 2 secondes
      setTimeout(() => location.reload(), 2000);
    } else {
      messageDiv.className = 'flash flash-error';
      const errors = result.errors ? Object.values(result.errors).join('<br>') : result.message;
      messageDiv.innerHTML = '<i class="bi bi-x-circle-fill"></i> ' + errors;
    }
  } catch (error) {
    messageDiv.style.display = 'block';
    messageDiv.className = 'flash flash-error';
    messageDiv.innerHTML = '<i class="bi bi-x-circle-fill"></i> Erreur serveur';
  }
});

// Gestion de la recherche et du filtrage
const searchInput = document.getElementById('search-input');
const filterDept = document.getElementById('filter-dept');
const employeRows = document.querySelectorAll('.employe-row');

function filterEmployes() {
  const searchTerm = searchInput.value.toLowerCase();
  const deptFilter = filterDept.value;
  
  employeRows.forEach(row => {
    const matchesSearch = row.dataset.search.includes(searchTerm);
    const matchesDept = !deptFilter || row.dataset.dept === deptFilter;
    
    row.style.display = (matchesSearch && matchesDept) ? '' : 'none';
  });
}

searchInput.addEventListener('input', filterEmployes);
filterDept.addEventListener('change', filterEmployes);
</script>
</body>
</html>
