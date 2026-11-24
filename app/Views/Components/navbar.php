<?php

$esc = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$brandHref = $brandHref ?? 'ContactController.php?action=list';
$headerCaption = $headerCaption ?? 'Organização pessoal / 01';
$headerLinkHref = $headerLinkHref ?? 'ContactController.php?action=create';
$headerLinkText = $headerLinkText ?? 'Novo contato';
$showPlus = $showPlus ?? false;
?>
    <header class="site-header">
        <a class="brand" href="<?= $esc($brandHref) ?>" aria-label="Agenda, ir para contatos">
            <span class="brand-mark" aria-hidden="true">A</span>
            <span class="brand-copy">
                <span class="brand-name">agenda</span>
                <span class="brand-caption">Caderno de contatos</span>
            </span>
        </a>
        <span class="header-caption"><?= $esc($headerCaption) ?></span>
        <a class="header-link" href="<?= $esc($headerLinkHref) ?>"><?php if ($showPlus): ?><span class="header-link-mark" aria-hidden="true">+</span> <?php endif; ?><?= $esc($headerLinkText) ?></a>
    </header>
