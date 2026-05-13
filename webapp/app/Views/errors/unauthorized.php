<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>TechMada RH — Accès refusé</title>
<link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet"/>
<link href="<?= base_url('assets/bootstrap/css/bootstrap-icons.min.css') ?>" rel="stylesheet"/>
<link href="<?= base_url('assets/css/css2.css') ?>" rel="stylesheet"/>
<link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet"/>
</head>
<body>
<section id="page-unauthorized">
<div class="auth-page geo-bg">
<div class="auth-split">

  <!-- Panneau gauche -->
  <div class="auth-left">
    <div>
      <p class="auth-left-brand">TechMada RH<span>Gestion des congés</span></p>
      <p class="auth-left-text" style="margin-top:2rem">
        <strong>Accès refusé.</strong>
        Vous n'avez pas les permissions nécessaires pour accéder à cette section.
      </p>
    </div>
  </div>

  <!-- Panneau droit -->
  <div class="auth-right">
    <div style="text-align:center;padding:3rem 0">
      <i class="bi bi-shield-exclamation" style="font-size:4rem;color:#dc3545;margin-bottom:1rem;display:block"></i>
      <p class="auth-title">403 — Accès Refusé</p>
      <p class="auth-sub">Vous n'avez pas les permissions nécessaires pour accéder à cette ressource.</p>
      
      <?php if (session()->has('error')): ?>
        <div style="margin-top:1rem;padding:1rem;background:#f8d7da;border-radius:0.5rem;color:#721c24">
          <i class="bi bi-exclamation-circle-fill"></i>
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <a href="/" class="btn-primary" style="display:inline-block;margin-top:2rem">
        <i class="bi bi-arrow-left-short"></i> Retour à l'accueil
      </a>
    </div>
  </div>

</div>
</div>
</section>
</body>
</html>
