<?php

$esc = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$brandHref = $brandHref ?? 'ContactController.php?action=list';
$headerLinkHref = $headerLinkHref ?? 'ContactController.php?action=create';
$headerLinkText = $headerLinkText ?? 'Novo contato';
$hideHeaderLink = $hideHeaderLink ?? false;
$currentUser = class_exists('Auth') ? Auth::user() : null;
$logoutUrl = '../Auth/AuthController.php?action=logout';
?>
    <header class="site-header">
        <a class="brand" href="<?= $esc($brandHref) ?>" aria-label="Agenda, ir para contatos">
            <span class="brand-mark" aria-hidden="true">A</span>
            <span class="brand-copy">
                <span class="brand-name">agenda</span>
                <span class="brand-caption">Caderno de contatos</span>
            </span>
        </a>
        <?php if (!$hideHeaderLink): ?>
        <a class="header-link" href="<?= $esc($headerLinkHref) ?>"><?= $esc($headerLinkText) ?></a>
        <?php endif; ?>
        <?php if ($currentUser !== null): ?>
        <div class="header-user">
            <span class="header-user-name" title="<?= $esc($currentUser['email']) ?>"><?= $esc($currentUser['name']) ?></span>
            <form action="<?= $esc($logoutUrl) ?>" method="post">
                <button class="button-link header-logout" type="submit">Sair</button>
            </form>
        </div>
        <?php endif; ?>
    </header>
