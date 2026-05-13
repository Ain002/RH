<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>TechMada RH — Complétez votre profil</title>
<link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet"/>
<link href="<?= base_url('assets/bootstrap/css/bootstrap-icons.min.css') ?>" rel="stylesheet"/>
<link href="<?= base_url('assets/css/css2.css') ?>" rel="stylesheet"/>
<link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet"/>
</head>
<body>
<section id="page-complete-profile">
<div class="auth-page geo-bg">
<div class="auth-split">

  <!-- Panneau gauche -->
  <div class="auth-left">
    <div>
      <p class="auth-left-brand">TechMada RH<span>Gestion des congés</span></p>
      <p class="auth-left-text" style="margin-top:2rem">
        <strong>Profil incomplet.</strong>
        Veuillez compléter vos informations pour accéder à votre espace de gestion des congés.
      </p>
    </div>
  </div>

  <!-- Panneau droit -->
  <div class="auth-right">
    <p class="auth-title">Complétez votre profil</p>
    <p class="auth-sub">Renseignez vos informations personnelles.</p>

    <form class="user" method="POST" action="<?= base_url('/profile/update') ?>" id="profileForm">
      <div class="f-group">
        <label class="f-label">Prénom</label>
        <input type="text" class="f-input" placeholder="Votre prénom" value="<?= $user['prenom'] ?? '' ?>" disabled/>
      </div>
      <div class="f-group">
        <label class="f-label">Nom</label>
        <input type="text" class="f-input" placeholder="Votre nom" value="<?= $user['nom'] ?? '' ?>" disabled/>
      </div>
      <div class="f-group">
        <label class="f-label">Taille (cm) *</label>
        <input type="number" class="f-input" name="taille" placeholder="170" value="<?= $user['taille'] ?? '' ?>" required/>
      </div>
      <div class="f-group">
        <label class="f-label">Poids (kg) *</label>
        <input type="number" class="f-input" name="poids" placeholder="70" value="<?= $user['poids'] ?? '' ?>" step="0.1" required/>
      </div>
      <button type="submit" class="btn-primary" style="margin-top:.5rem">
        Terminer <i class="bi bi-arrow-right-short"></i>
      </button>
    </form>
  </div>

</div>
</div>
</section>
</body>
</html>
