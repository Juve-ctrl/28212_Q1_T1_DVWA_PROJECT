<?php

header(
    "Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'"
);

$page['body'] .= '
<form name="csp" method="POST">
    <p>The hardened policy allows scripts only from this origin and blocks inline scripts.</p>
    <p>1+2+3+4+5=<span id="answer"></span></p>
    <input type="button" id="solve" value="Solve the sum" />
</form>
<script src="source/impossible.js"></script>
';
