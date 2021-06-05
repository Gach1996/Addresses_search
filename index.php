<?php

require_once dirname(__DIR__) . '/Task/app/db_connection.php';
require_once dirname(__DIR__) . '/Task/app/distance.php';
require_once dirname(__DIR__) . '/Task/app/content.php';

function dd($res){
    echo '<pre>';
    print_r($res);
    die;
}

$connection = connectionDb();

if(isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $sql = "SELECT id, addresses_street, addresses_address FROM addresses1 WHERE id != $id";

    $result = mysqli_query($connection, $sql);

    if (isset($result)) {
        $addresses = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $tableAddressesHtml = '';
        $tableAddresses = [];

        foreach($addresses as $key => $address) {

            $addressName = $address['addresses_street'] ." ". $address['addresses_address'];
            $distance = getDistance($id, $address['id']);

            $td = '';

            if ($distance < 5) {
                $tableAddresses['5'][$key]['distance'] = $distance;
                $tableAddresses['5'][$key]['name'] = $addressName;
                $td .= "<td data-distance='$distance'>$addressName ($distance km)</td>";
            }

            if ($distance >= 5 && $distance <= 30) {
                $tableAddresses['5_30'][$key]['distance'] = $distance;
                $tableAddresses['5_30'][$key]['name'] = $addressName;
                $td .= "<td data-distance='$distance'>$addressName ($distance km)</td>";
            }

            if ($distance > 30) {
                $tableAddresses['30'][$key]['distance'] = $distance;
                $tableAddresses['30'][$key]['name'] = $addressName;
                $td .= "<td data-distance='$distance'>$addressName ($distance km)</td>";
            }

            $tableAddressesHtml .= "<tr>$td</tr>";
        }

        $count5 = count($tableAddresses['5']);
        $count5_30 = count($tableAddresses['5_30']);
        $count30 = count($tableAddresses['30']);

        if ($count5 > $count5_30 && $count5 > $count30) {
            $count = $count5;
        } elseif($count5_30 > $count5 && $count5_30 > $count30) {
            $count = $count5_30;
        } else {
            $count = $count30;
        }
    }

}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
    <title>Task_2</title>
    <style>

        table, td, th {
            border: 1px solid black;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            height: 30px;
            width: 10%;
        }
        .address_row:hover {
            background-color: #000;
            color: #fff;
            cursor: pointer;
        }
        #ceo th,
        #ceo td {
            padding: 10px 30px;
        }

        #ceo th {
            background: #333;
            color: white;
        }

        #ceo th.asc:after {
            display: inline;
            content: '↓';
        }

        #ceo th.desc:after {
            display: inline;
            content: '↑';
        }

        #ceo td {
            background: #ccc;
        }

    </style>
    <script>

        function funcBefore() {
            $("#information .message").text("Ожидание данных...");
        }

        function funcSuccess(data) {
                $(".table").remove();
                $(" .table-head").html('');
                $(" .table-body").html('');
                $(".table-head").append("<tr>" +
                    "<th>" + 'id' + "</th>" +
                    "<th>" + 'addresses_id' + "</th>" +
                    "<th>" + 'addresses_address' + "</th>" +
                    "<th>" + 'addresses_street' + "</th>" +
                    "<th>" + 'addresses_street_name' + "</th>" +
                    "<th>" + 'addresses_street_type' + "</th>" +
                    "<th>" + 'addresses_adm' + "</th>" +
                    "<th>" + 'addresses_adm1' + "</th>" +
                    "<th>" + 'addresses_adm2' + "</th>" +
                    "<th>" + 'addresses_cord_y' + "</th>" +
                    "<th>" + 'addresses_cord_x' + "</th>" +
                    "</tr>");

            if (data) {
                $(data).each(function(key, item){
                    $(".table-body").append("<tr class='address_row' data-id="+item.id+">" +
                        "<td>" + item.id + "</td>" +
                        "<td>" + item.addresses_id + "</td>" +
                        "<td>" + item.addresses_address + "</td>" +
                        "<td>" + item.addresses_street + "</td>" +
                        "<td>" + item.addresses_street_name + "</td>" +
                        "<td>" + item.addresses_street_type + "</td>" +
                        "<td>" + item.addresses_adm + "</td>" +
                        "<td>" + item.addresses_adm1 + "</td>" +
                        "<td>" + item.addresses_adm2 + "</td>" +
                        "<td>" + item.addresses_cord_y + "</td>" +
                        "<td>" + item.addresses_cord_x + "</td>" +
                        // '<td><form action="file_1.php" method="get"> <input type="hidden" value="'+item.id+'" name="id"> <button class="btn btn-secondary" type="submit">select </button> </form></td>' +
                        "</tr>");
                });
            }
            $("#information .message").text("");
            // $("#information").html(data);
        }

        $(document).ready(function () {
            $("#done").bind("click", function () {
                $.ajax({
                    url: "/Task/app/content.php",
                    dataType: "json",
                    data: {name: $("#name").val()},
                    type: "get",
                    beforeSend: funcBefore,
                    success: funcSuccess
                });
            });

            $(document).on('click', '.address_row', function () {
                var clickedId = $(this).attr('data-id');
                $('#result_form_input').val(clickedId)
                $('#result_form').submit();
            })
        });
    </script>

</head>
<body>

<input type="text" id="name" placeholder="Введите текст">
<input type="button" id="done" value="Готово">
<div id="information">
    <p class="message"></p>
    <table class="tab">
        <thead class="table-head"></thead>
        <tbody class="table-body"></tbody>
    </table>
</div>

<form action="index.php" method="get" id="result_form">
    <input type="hidden" value="" name="id" id="result_form_input">
</form>
<?php if(isset($_GET['id'])):?>
    <div class="col-lg-12 my-3 p-3 bg-white rounded shadow-sm ">
        <table class="table table-striped table-dark" id="addresses">
            <thead >
            <tr>
                <th scope="col">Distance < 5 Km</th>
                <th scope="col">Distance From 5 Km to 30 Km</th>
                <th scope="col">Distance more than 30 Km</th>
            </tr>
            </thead>
            <tbody>
            <?php for($i = 0; $i <= $count; $i++):?>
            <?php
                $address5 = array_values($tableAddresses['5']);
                $address5_30 = array_values($tableAddresses['5_30']);
                $address30 = array_values($tableAddresses['30']);

                ?>

            <tr>
                <td data-distance='<?=isset($address5[$i]) ? $address5[$i]['distance'] : ''?>'>
                    <?php if (isset($address5[$i])): ?>
                        <?= $address5[$i]['name'] . ' (' . $address5[$i]['distance'] . ' km)' ?>
                    <?php endif; ?>
                </td>
                <td data-distance='<?=isset($address5_30[$i]) ? $address5_30[$i]['distance'] : ''?>'>
                    <?php if (isset($address5_30[$i])): ?>
                        <?= $address5_30[$i]['name'] . ' (' . $address5_30[$i]['distance'] . ' km)' ?>
                    <?php endif; ?>
                </td>
                <td data-distance='<?=isset($address30[$i]) ? $address30[$i]['distance'] : ''?>'>
                    <?php if (isset($address30[$i])): ?>
                        <?= $address30[$i]['name'] . ' (' . $address30[$i]['distance'] . ' km)' ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endfor;?>
            </tbody>
        </table>
    </div>
<?php endif;?>
<script>
    $(function () {
        $('table')
            .on('click', 'th', function () {
                var index = $(this).index(),
                    rows = [],
                    thClass = $(this).hasClass('asc') ? 'desc' : 'asc';

                $('#addresses th').removeClass('asc desc');
                $(this).addClass(thClass);

                $('#addresses tbody tr').each(function (index, row) {
                    rows.push($(row).detach());
                });

                rows.sort(function (a, b) {

                    var aValue = $(a).find('td').eq(index).attr('data-distance'),
                        bValue = $(b).find('td').eq(index).attr('data-distance');

                    return aValue > bValue
                        ? 1
                        : aValue < bValue
                            ? -1
                            : 0;
                });

                if ($(this).hasClass('desc')) {
                    rows.reverse();
                }

                $.each(rows, function (index, row) {
                    $('#addresses tbody ').append(row);
                });

            });
    });
</script>

</body>
</html>
