<?php
/**
 * /assets/ssl-provider-fees-missing.php
 *
 * This file is part of DomainMOD, an open source domain and internet asset manager.
 * Copyright (c) 2010-2018 Greg Chetcuti <greg@chetcuti.com>
 *
 * Project: http://domainmod.org   Author: http://chetcuti.com
 *
 * DomainMOD is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * DomainMOD is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with DomainMOD. If not, see
 * http://www.gnu.org/licenses/.
 *
 */
?>
<?php
require_once __DIR__ . '/../_includes/start-session.inc.php';
require_once __DIR__ . '/../_includes/init.inc.php';
require_once DIR_INC . '/config.inc.php';
require_once DIR_INC . '/software.inc.php';
require_once DIR_ROOT . '/vendor/autoload.php';

$deeb = DomainMOD\Database::getInstance();
$form = new DomainMOD\Form();
$system = new DomainMOD\System();
$time = new DomainMOD\Time();

require_once DIR_INC . '/head.inc.php';
require_once DIR_INC . '/debug.inc.php';
require_once DIR_INC . '/settings/assets-ssl-provider-fees-missing.inc.php';

$system->authCheck();
$pdo = $deeb->cnxx;
?>
<?php require_once DIR_INC . '/doctype.inc.php'; ?>
<html>
<head>
    <title><?php echo $system->pageTitle($page_title); ?></title>
    <?php require_once DIR_INC . '/layout/head-tags.inc.php'; ?>
</head>
<body class="hold-transition skin-red sidebar-mini">
<?php require_once DIR_INC . '/layout/header.inc.php'; ?>
<?php
$result = $pdo->query("
    SELECT sp.id AS ssl_provider_id, sp.name AS ssl_provider_name
    FROM ssl_providers sp, ssl_certs sc
    WHERE sp.id = sc.ssl_provider_id
      AND sc.fee_id = '0'
    GROUP BY sp.name
    ORDER BY sp.name ASC")->fetchAll();
?>
The following SSL Certificates are missing fees. In order to ensure your SSL reporting is accurate please update these
fees as soon as possible.<BR>

<table id="<?php echo $slug; ?>" class="<?php echo $datatable_class; ?>">
    <thead>
    <tr>
        <th width="20px"></th>
        <th>Provider</th>
        <th>Missing Fees</th>
    </tr>
    </thead>
    <tbody><?php

    foreach ($result as $row) { ?>

        <tr>
        <td></td>
        <td>
            <?php echo $row->ssl_provider_name; ?>
        </td>
        <td><?php

            $result_missing_types = $pdo->query("
                SELECT sslct.id, sslct.type
                FROM ssl_certs AS sslc, ssl_cert_types AS sslct
                WHERE sslc.type_id = sslct.id
                  AND sslc.ssl_provider_id = '" . $row->ssl_provider_id . "'
                  AND sslc.fee_id = '0'
                GROUP BY sslct.type
                ORDER BY sslct.type ASC")->fetchAll();

            $full_type_list = "";

            foreach ($result_missing_types as $row_missing_types) {

                $full_type_list .= '<a href=\'' . $web_root . '/assets/add/ssl-provider-fee.php?sslpid=' . $row->ssl_provider_id . '&type_id=' . $row_missing_types->id . '\'>' . $row_missing_types->type . "</a>, ";

            }

            $full_type_list_formatted = substr($full_type_list, 0, -2); ?>
            <a href="ssl-provider-fees.php?sslpid=<?php echo $row->ssl_provider_id; ?>"><?php echo $full_type_list_formatted; ?></a>
        </td>
        </tr><?php

    } ?>

    </tbody>
</table>
<?php require_once DIR_INC . '/layout/footer.inc.php'; ?>
</body>
</html>
