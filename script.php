<?php

require_once (__DIR__ . '/vk.php');
$vk = new VK();

$mysqli = new mysqli('localhost', 'bot_for_monopoly', 'bot_for_monopoly', 'monopoly');
$mysqli->set_charset('utf8');

if ($vk->data->type == 'message_new') {
    $peer_id = $vk->data->object->message->peer_id;
    $user_id = $vk->data->object->message->from_id;
    $text = mb_strtolower($vk->data->object->message->text, 'UTF-8');

    $vk_donut = $mysqli->query("SELECT `peer_id` FROM `monopoly`.`vk_donut` WHERE `peer_id` = '$peer_id' OR `peer_id` = '$user_id'");
    $vk_donut = $vk_donut->fetch_row();

    // Игровой блок
    require_once (__DIR__ . '/monopoly/monopoly.php');
    $monopoly = new Monopoly();
    $monopoly->vk = $vk;
    $monopoly->mysqli = $mysqli;
    $check_table = $mysqli->query("CHECK TABLE `monopoly`.`{$peer_id}_game_parameters`");
    $check_table = $check_table->fetch_row();
    if ($check_table[2] == 'Error' and $peer_id > 2000000000)
    {
        if ($vk_donut[0] < 2000000000)
        {
            switch (true)
            {
                case ($text == 'давай сыграем в монополию'):
                    $vk->messages_send($peer_id, 'В каком формате будем собирать команды?', '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Игра на двоих"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Игра на троих"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Игра на четверых"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Игра два на два"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отмена"},"color":"negative"}]]}');
                    $command = true;
                    break;
                case ($text == '[club215797262|@club215797262] отмена'):
                    $vk->messages_send($peer_id, 'Отменять что? Игры нет никакой!', '', '');
                    $command = true;
                    break;
                case ($text == '[club215797262|@club215797262] игра на двоих'):
                    $keyboard = '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Красный"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Синий"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отмена"},"color":"negative"}]]}';
                    $type_game = 2;
                    break;
                case ($text == '[club215797262|@club215797262] игра на троих'):
                    $keyboard = '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Красный"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Синий"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Зелёный"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отмена"},"color":"negative"}]]}';
                    $type_game = 3;
                    break;
                case ($text == '[club215797262|@club215797262] игра на четверых'):
                    $keyboard = '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Красный"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Синий"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Зелёный"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Фиолетовый"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отмена"},"color":"negative"}]]}';
                    $type_game = 4;
                    break;
                case ($text == '[club215797262|@club215797262] игра два на два'):
                    $keyboard = '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Красный"},"color":"positive"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Синий"},"color":"primary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Зелёный"},"color":"positive"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Фиолетовый"},"color":"primary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отмена"},"color":"negative"}]]}';
                    $type_game = 5;
                    break;
            }
            if (isset($type_game))
            {
                $vk->messages_send($peer_id, 'Игроки, разбирайте цвета для своих полей и фигур.', '', $keyboard);
                $mysqli->query("CREATE TABLE `monopoly`.`{$peer_id}_game_parameters` LIKE `monopoly`.`sample_game_parameters`;");
                $mysqli->query("INSERT INTO `monopoly`.`{$peer_id}_game_parameters` SELECT * FROM `monopoly`.`sample_game_parameters`;");
                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = '$type_game' WHERE `ID` = 1;");
                $mysqli->query("CREATE TABLE `monopoly`.`{$peer_id}_players` ( `user_id` INT(10) NOT NULL AUTO_INCREMENT , `color` INT(2) NOT NULL , `money` FLOAT(10) NOT NULL , `cell` INT(2) NOT NULL , `refused_the_auction` INT(1) NOT NULL , `arrest` INT(1) NOT NULL , `jackpot` JSON NULL , `deal` JSON NULL , PRIMARY KEY (`user_id`)) ENGINE = InnoDB;");
                $mysqli->query("CREATE TABLE `monopoly`.`{$peer_id}_map` LIKE `monopoly`.`sample_map`;");
                $mysqli->query("INSERT INTO `monopoly`.`{$peer_id}_map` SELECT * FROM `monopoly`.`sample_map`;");
                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `ID` = `ID` - 1;");
                $command = true;
            }
        }
        else
            switch (true)
            {
                case ($text == 'давай сыграем в монополию'):
                    $vk->messages_send($peer_id, 'Игру в монополию могут начать только доны.', '', '');
                    $command = true;
                    break;
                case (strpos($text, '[club215797262|@club215797262] игра ') !== false):
                    $vk->messages_send($peer_id, 'Режим игры могут выбирать только доны.', '', '');
                    $command = true;
                    break;
                case ($text == '[club215797262|@club215797262] отмена'):
                    $vk->messages_send($peer_id, 'Игру могут отменять только доны.', '', '');
                    $command = true;
                    break;
            }
    }
    else
    {
        if ($vk_donut[0] < 2000000000)
            switch (true)
            {
                case ($text == 'давай сыграем в монополию' or strpos($text, '[club215797262|@club215797262] игра ') !== false):
                    $vk->messages_send($peer_id, 'Игра уже сформирована.', '', '');
                    $command = true;
                    break;
                case ($text == '[club215797262|@club215797262] отмена'):
                    $mysqli->query("DROP TABLE `monopoly`.`{$peer_id}_game_parameters`");
                    $mysqli->query("DROP TABLE `monopoly`.`{$peer_id}_players`");
                    $mysqli->query("DROP TABLE `monopoly`.`{$peer_id}_map`");
                    $vk->messages_send($peer_id, 'Хорошо, поиграем потом...', '', '');
                    exit();
            }

        $type_game = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `parameters` = 'type_game'");
        $type_game = $type_game->fetch_row();
        $set_color = array(1 => '[club215797262|@club215797262] красный', '[club215797262|@club215797262] синий', '[club215797262|@club215797262] зелёный', '[club215797262|@club215797262] фиолетовый');
        if (in_array($text, $set_color))
        {
            $color = array_search($text, $set_color);
            $check_user = $mysqli->query("SELECT `user_id` FROM `monopoly`.`{$peer_id}_players` WHERE `color` = '$color' OR `user_id` = '$user_id'");
            $check_user = $check_user->fetch_row();
            $checking_game_conditions = $monopoly->checking_game_conditions(array(
                'in_the_current_format_of_the_game_this_color_cannot_be_selected' => array($color, $type_game[0]),
                'user_is_already_in_the_game' => array($check_user)
            ));

            if (!is_array($checking_game_conditions))
            {
                $mysqli->query("INSERT INTO `monopoly`.`{$peer_id}_players` (`user_id`, `color`, `money`, `cell`, `refused_the_auction`, `arrest`, `deal`) VALUES ('$user_id', '$color', '15000', '0', '0', '0', '{\"color\":null,\"money_give\":0,\"money_get\":0,\"give\":[],\"get\":[]}');");
                $vk->messages_send($peer_id, 'Хорошо, закрепил за [id' . $user_id . '|тобой] выбранный цвет.', '', '');
                $start_game = true;
            }
            else
                $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
            $command = true;
        }

        $count_players = $mysqli->query("SELECT count(*) FROM `monopoly`.`{$peer_id}_players`");
        $count_players = $count_players->fetch_row();
        $player_info = $mysqli->query("SELECT * FROM `monopoly`.`{$peer_id}_players` WHERE `user_id` = $user_id");
        $player_info = $player_info->fetch_row();
        $load_game = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 10");
        $load_game = $load_game->fetch_row();
        if (($count_players[0] == $type_game[0] or $count_players[0] == 4 or $load_game[0]) and is_array($player_info))
        {
            if (isset($start_game))
            {
                $photos_save_messages_photo = $monopoly->assembling_a_game_card($peer_id, "Баланс всех игроков: 15.000\nБросает кубики: Красный\n\nЧтобы закончить игру раньше напишите:\n[club215797262|@club215797262] сдаться", '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бросить кубики"},"color":"positive"}]]}');
                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 1 WHERE `ID` = 10");
            }

            $current_move = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 2");
            $current_move = $current_move->fetch_row();
            $waiting_for_a_move = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 3");
            $waiting_for_a_move = $waiting_for_a_move->fetch_row();
            $building_a_branch = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 4");
            $building_a_branch = $building_a_branch->fetch_row();
            $field_participating_in_auction = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 5");
            $field_participating_in_auction = $field_participating_in_auction->fetch_row();
            $cost_of_the_field_at_auction = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 6");
            $cost_of_the_field_at_auction = $cost_of_the_field_at_auction->fetch_row();
            $fine = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 8");
            $fine = $fine->fetch_row();
            $duplicate = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 9");
            $duplicate = $duplicate->fetch_row();
            $field_info = $mysqli->query("SELECT * FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `ID` = $player_info[3]");
            $field_info = $field_info->fetch_row();
            $field_cost = $mysqli->query("SELECT `field_cost` FROM `monopoly`.`information_about_fields` WHERE `ID` = $player_info[3];");
            $field_cost = $field_cost->fetch_row();
            if (!$field_participating_in_auction[0])
            {
                switch (true)
                {
                    case ($text == '[club215797262|@club215797262] бросить кубики'):
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                            'it_cant_be_done_now' => array($waiting_for_a_move[0])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            $cell = $player_info[3];
                            for ($i = 0; $i < 2; $i++)
                                $dice[$i] = rand(1, 6);

                            if ($player_info[5])
                            {
                                if ($dice[0] == $dice[1] and $player_info[5] < 3)
                                {
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `arrest` = 0 WHERE `user_id` = $user_id;");
                                    $color = $monopoly->current_move($peer_id);
                                    $monopoly->assembling_a_game_card($peer_id, "Вы выходите из тюрьмы выбросив на кубиках $dice[0] и $dice[1]. Теперь можете бросить кубики снова!\n\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color[1]", '');
                                } else
                                {
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `arrest` = `arrest` + 1 WHERE `user_id` = $user_id;");
                                    if ($player_info[5] < 3)
                                    {
                                        $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                        $monopoly->assembling_a_game_card($peer_id, "Вам не удалось выйти из тюрьмы.\n\nНа кубиках выпало: $dice[0] и $dice[1]\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                    } else
                                        $monopoly->assembling_a_game_card($peer_id, "Вам не удалось выбросить дубль за 3 попытки и теперь чтобы покинуть тюрьму вам придётся заплатить 500k.\n" . $monopoly->player_balance($peer_id), '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Выйти из тюрьмы за 500k"},"color":"negative"}]]}');
                                }
                            } else
                            {
                                if ($player_info[3] + array_sum($dice) != 41)
                                    $player_info[3] = ($player_info[3] + array_sum($dice)) % 41;
                                else
                                    $player_info[3] = 0;
                                if ($player_info[3] > 10 and $cell < 11)
                                    $player_info[3]++;
                                if ($cell > 28 and $player_info[3] < 13)
                                {
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` + 2000 WHERE `user_id` = $user_id");
                                    $message = "За прохождение круга вы получаете бонусные 2,000k\n\n";
                                } else
                                    $message = '';
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `cell` = $player_info[3] WHERE `color` = $player_info[1];");
                                if ($duplicate[0] > 2)
                                {
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 0 WHERE `ID` = 9");
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `cell` = '11', `arrest` = '1' WHERE `user_id` = $user_id;");
                                    $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                    $monopoly->assembling_a_game_card($peer_id, $message . "Вы выбросили дубль 3 раза подряд и отправляетесь в тюрьму за совершение махинаций.\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                    exit();
                                }
                                if ($dice[0] == $dice[1])
                                {
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = `value` + 1 WHERE `ID` = 9");
                                    $message = "Вы выбрасиваете дубль и можете ходить ещё раз!\n\n";
                                } else
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 0 WHERE `ID` = 9");

                                $field_info = $mysqli->query("SELECT `name`, `recruiter`, `level`, `franchise` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `ID` = $player_info[3]");
                                $field_info = $field_info->fetch_row();
                                switch ($field_info[0])
                                {
                                    case 'start':
                                        if ($message)
                                        {
                                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` + 1000 WHERE `user_id` = $user_id");
                                            $message = "Вы попадаете на поле старт и за прохождение круга получаете бонусные 3,000k\n\n";
                                        }
                                        $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                        $monopoly->assembling_a_game_card($peer_id, $message . "На кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                        break;
                                    case 'tax_income':
                                        $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 1 WHERE `{$peer_id}_game_parameters`.`ID` = 3;");
                                        $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 2000 WHERE `{$peer_id}_game_parameters`.`ID` = 8;");
                                        $monopoly->assembling_a_game_card($peer_id, $message . 'Вы попадаете на поле подоходный налог и должны заплатить банку 2,000k', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Оплатить"},"color":"positive"}]]}');
                                        break;
                                    case 'police_station':
                                        $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                        $monopoly->assembling_a_game_card($peer_id, $message . "Вы посещаете полицейский участок с экскурсией\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                        break;
                                    case 'jackpot':
                                        $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 1 WHERE `{$peer_id}_game_parameters`.`ID` = 3;");
                                        $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `jackpot` = '{\"jackpot\":true,\"element\":{}}' WHERE `user_id` = $user_id;");
                                        $monopoly->assembling_a_game_card($peer_id, $message . "Вы попадаете в казино.\nВыберите от 1 до 3 чисел и испытайте удачу. Если угадаете выпавшее число, то получите выигрыш.\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id), '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 1"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 2"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 3"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 4"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 5"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 6"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отказаться"},"color":"negative"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Поставить 1,000k"},"color":"positive"}]]}');
                                        break;
                                    case 'police':
                                        $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `cell` = '11', `arrest` = '1' WHERE `user_id` = $user_id;");
                                        $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                        $monopoly->assembling_a_game_card($peer_id, $message . "Вы арестованы полицией и отправляетесь в тюрьму\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                        break;
                                    case 'tax_luxury':
                                        $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 1 WHERE `{$peer_id}_game_parameters`.`ID` = 3;");
                                        $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 1000 WHERE `{$peer_id}_game_parameters`.`ID` = 8;");
                                        $monopoly->assembling_a_game_card($peer_id, $message . 'Вы попадаете на поле "Налог на роскошь" и должны заплатить банку 1,000k', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Оплатить"},"color":"positive"}]]}');
                                        break;
                                    case 'chance':
                                        $rand = rand(1, 16);
                                        switch ($rand)
                                        {
                                            case 1:
                                            case 2:
                                            case 3:
                                            case 4:
                                            case 5:
                                            case 6:
                                                $money = array(
                                                    1 => array('Вы попадаете на поле шанс. Вы участвуете в лотерее и выигрываете 500k.', 500),
                                                    array('Вы попадаете на поле шанс. Вы участвуете в лотерее и выигрываете 1,500k.', 1500),
                                                    array('Вы попадаете на поле шанс. Вы занимаете первое место в конкурсе красоты и получаете 1,000k.', 1000),
                                                    array('Вы попадаете на поле шанс. Вы занимаете второе место в конкурсе красоты и получаете 500k.', 500),
                                                    array('Вы попадаете на поле шанс. Банк выплачивает Вам диведенты на сумму 1,500k.', 1500),
                                                    array('Вы попадаете на поле шанс. Вы находите 125k мелочью.', 125)
                                                );
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` + {$money[$rand][1]} WHERE `user_id` = $user_id");
                                                $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                                $monopoly->assembling_a_game_card($peer_id, $message . "{$money[$rand][0]}\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                                break;
                                            case 7:
                                                $money = $mysqli->query("SELECT COUNT(*) FROM `monopoly`.`{$peer_id}_players` WHERE `money` >= 500");
                                                $money = $money->fetch_row();
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` - 500 WHERE `money` >= 500");
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` + " . 500 * $money[0] . " WHERE `user_id` = $user_id");
                                                $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                                $monopoly->assembling_a_game_card($peer_id, $message . "Вы попадаете на поле шанс. У Вас сегодня день рождения и все игроки скидываются вам по 500k.\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                                break;
                                            case 8:
                                                $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                                $monopoly->assembling_a_game_card($peer_id, $message . "Вы попадаете на поле шанс. Вы теряете свои кубики. Поиски займут целых ход.\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                                break;
                                            case 9:
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `cell` = " . rand(0, 40) . " WHERE `user_id` = $user_id");
                                                $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                                $monopoly->assembling_a_game_card($peer_id, $message . "Вы попадаете на поле шанс. Вы участвуете в тестировании телепортации.\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                                break;
                                            case 10:
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `cell` = '11', `arrest` = '1' WHERE `user_id` = $user_id;");
                                                $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                                $monopoly->assembling_a_game_card($peer_id, $message . "Вы попадаете на поле шанс. Вы арестованы за отмывание денег и отправляетесь в тюрьму.\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                                break;
                                            case 11:
                                                $litle_star = $mysqli->query("SELECT IFNULL(SUM(`level`), 0) FROM `monopoly`.`{$peer_id}_map` WHERE `level` > 0 AND `level` < 5 AND `recruiter` = $player_info[1]");
                                                $litle_star = $litle_star->fetch_row();
                                                $big_star = $mysqli->query("SELECT COUNT(*) FROM `monopoly`.`{$peer_id}_map` WHERE `level` = 5 AND `recruiter` = $player_info[1]");
                                                $big_star = $big_star->fetch_row();
                                                $money = $litle_star[0] * 250 + $big_star[0] * 1000;
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 1 WHERE `ID` = 3;");
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = $money WHERE `ID` = 8;");
                                                $monopoly->assembling_a_game_card($peer_id, $message . "Вы попадаете на поле шанс. Вам необходимо провести капитальный ремонт своих предприятий. Необходимо заплатить по 250k за каждую малую звезду и 1,000k за каждую большую. Итого: " . number_format($money, 0, ',', ',') . "k.\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id), '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Оплатить"},"color":"positive"}]]}');
                                                break;
                                            case 12:
                                            case 13:
                                            case 14:
                                            case 15:
                                            case 16:
                                                $money = array(
                                                    12 => array('Вы попадаете на поле шанс. Вам необходимо продлить свою страховку за 1,500k.', 1500),
                                                    array('Вы попадаете на поле шанс. Вы решили сыграть в казино и проиграли 1,000k.', 1000),
                                                    array('Вы попадаете на поле шанс. Вы решили сыграть в казино и проиграли 500k.', 500),
                                                    array('Вы попадаете на поле шанс. Вы попадаете на распродажу и тратите 500k.', 500),
                                                    array('Вы попадаете на поле шанс. Вы должны оплатить расходы на образование в размере 500k.', 500)
                                                );
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 1 WHERE `ID` = 3;");
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = {$money[$rand][1]} WHERE `ID` = 8;");
                                                $monopoly->assembling_a_game_card($peer_id, $message . "{$money[$rand][0]}\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id), '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Оплатить"},"color":"positive"}]]}');
                                                break;
                                        }
                                        break;
                                    default:
                                        switch (true)
                                        {
                                            case ($field_info[2] < 0):
                                                $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                                $monopoly->assembling_a_game_card($peer_id, $message . "Поле заложено, поэтому ничего платить не нужно.\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                                break;
                                            case ($field_info[1] == $player_info[1] or ($type_game[0] == 5 and (($field_info[1] % 2 == 0 and $player_info[1] % 2 == 0) or ($field_info[1] % 2 != 0 and $player_info[1] % 2 != 0)))):
                                                $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                                $monopoly->assembling_a_game_card($peer_id, $message . "На кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                                                break;
                                            case ($field_info[1] and $field_info[1] != $player_info[1]):
                                                switch (true)
                                                {
                                                    case ($field_info[3] == 'cars'):
                                                        $rent_cars = array(1 => 250, 500, 1000, 2000);
                                                        $rent = $mysqli->query("SELECT COUNT(*) FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `franchise` = 'cars' AND `recruiter` = (SELECT `recruiter` FROM `monopoly`.`{$peer_id}_map` WHERE `name` = '$field_info[0]')");
                                                        $rent = $rent->fetch_row();
                                                        $rent = $rent_cars[$rent[0]];
                                                        break;
                                                    case ($field_info[3] == 'game studios'):
                                                        $rent = $mysqli->query("SELECT COUNT(*) FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `franchise` = 'game studios' AND `recruiter` = (SELECT `recruiter` FROM `monopoly`.`{$peer_id}_map` WHERE `name` = '$field_info[0]')");
                                                        $rent = $rent->fetch_row();
                                                        if ($rent[0] == 1)
                                                            $rent = array_sum($dice) * 100;
                                                        else
                                                            $rent = array_sum($dice) * 250;
                                                        break;
                                                    default:
                                                        $rent = $mysqli->query("SELECT `rent` FROM `monopoly`.`information_about_fields` WHERE `name` = '$field_info[0]'");
                                                        $rent = $rent->fetch_row();
                                                        $rent = json_decode($rent[0]);
                                                        $rent = $rent->rent[$field_info[2]];
                                                        break;
                                                }
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 1 WHERE `{$peer_id}_game_parameters`.`ID` = 3;");
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = $rent WHERE `{$peer_id}_game_parameters`.`ID` = 8;");
                                                $monopoly->assembling_a_game_card($peer_id, $message . "Вы попадаете на поле другого игрока и должны выплатить аренду: " . number_format($rent, 0, ',', ',') . "k\n\nНа кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id), '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Оплатить"},"color":"positive"}]]}');
                                                break;
                                            default:
                                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 1 WHERE `{$peer_id}_game_parameters`.`ID` = 3;");
                                                $monopoly->assembling_a_game_card($peer_id, $message . "На кубиках выпало: $dice[0] и $dice[1]\nСуммарно: " . array_sum($dice) . "\n" . $monopoly->player_balance($peer_id), '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Выкупить поле"},"color":"positive"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"На аукцион"},"color":"negative"}]]}');
                                                break;
                                        }
                                        break;
                                }
                            }
                        } else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] оплатить'):
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                            'insufficient_funds' => array($player_info[2], $fine[0]),
                            'presence_of_a_fine' => array($fine[0])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 0 WHERE `ID` = 8 AND `ID` = 3");
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` - $fine[0] WHERE `user_id` = $user_id");
                            if ($field_info[2])
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` + $fine[0] WHERE `color` = $field_info[2]");
                            $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                            $monopoly->assembling_a_game_card($peer_id, "Оплачено!\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                        } else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] выкупить поле'):
                        $field_info = $mysqli->query("SELECT `recruiter` FROM `monopoly`.`{$peer_id}_map` WHERE `ID` = $player_info[3]");
                        $field_info = $field_info->fetch_row();
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                            'insufficient_funds' => array($player_info[2], $field_cost[0]),
                            'field_has_already_been_redeemed' => array($field_info[0])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `recruiter` = '$player_info[1]' WHERE `ID` = $player_info[3];");
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 0 WHERE `ID` = 3;");
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` - $field_cost[0] WHERE `user_id` = $user_id;");

                            $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                            $monopoly->assembling_a_game_card($peer_id, "Поле выкуплено\nБросает кубики: $color\n" . $monopoly->player_balance($peer_id), '');
                        } else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', $checking_game_conditions[1]);
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] на аукцион'):
                        $field_info = $mysqli->query("SELECT `recruiter` FROM `monopoly`.`{$peer_id}_map` WHERE `ID` = $player_info[2];");
                        $field_info = $field_info->fetch_row();
                        $cost_of_the_field_at_auction = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 5");
                        $cost_of_the_field_at_auction = $cost_of_the_field_at_auction->fetch_row();
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                            'field_cannot_be_auctioned' => array($field_info),
                            'field_is_already_participating_in_the_auction' => array($cost_of_the_field_at_auction[0])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            $field_cost[0] += 100;
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = '$player_info[3]' WHERE `ID` = 5;");
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = '$field_cost[0]' WHERE `ID` = 6;");
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `refused_the_auction` = '1' WHERE `user_id` = $user_id;");
                            $current_move = $mysqli->query("SELECT `color` FROM `monopoly`.`{$peer_id}_players` WHERE `refused_the_auction` = 0;");
                            $current_move = $current_move->fetch_row();
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = '$current_move[0]' WHERE `ID` = 7;");
                            $count_players = $mysqli->query("SELECT count(*) FROM `monopoly`.`{$peer_id}_players` WHERE `refused_the_auction` = 0;");
                            $count_players = $count_players->fetch_row();
                            if ($count_players[0] == 1)
                                $vk->messages_send($peer_id, "Поле разыгрывается на аукционе!\nТекущая стоимость: " . number_format($field_cost[0], 0, ',', ',') . "k\nВыбор делает: " . $monopoly->print_color($current_move[0]), '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Выкупить поле"},"color":"positive"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отказаться от участия"},"color":"negative"}]]}');
                            else
                                $vk->messages_send($peer_id, "Поле разыгрывается на аукционе!\nТекущая стоимость: " . number_format($field_cost[0], 0, ',', ',') . "k\nВыбор делает: " . $monopoly->print_color($current_move[0]), '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Повысить ставку"},"color":"positive"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отказаться от участия"},"color":"negative"}]]}');
                        } else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] выйти из тюрьмы за 500k'):
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                            'insufficient_funds' => array($player_info[2], 500),
                            'you_are_not_under_arrest' => array($player_info[5])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` - 500, `arrest` = 0 WHERE `user_id` = $user_id");
                            $color = $monopoly->current_move($peer_id);
                            $monopoly->assembling_a_game_card($peer_id, "Вы выходите из тюрьмы и теперь можете бросить кубики!\n\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color[1]", '');
                        } else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case (strpos($text, '[club215797262|@club215797262] 🎲 ') !== false and mb_strlen($text) == 30):
                        $number = mb_substr($text, 29);
                        $player_info[6] = json_decode($player_info[6]);
                        $player_info[6]->element = (array)$player_info[6]->element;
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                            'number_out_of_range' => array($number),
                            'you_cant_lace_a_bet' => array($player_info[6])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            if (in_array($number, $player_info[6]->element))
                                unset($player_info[6]->element[array_search($number, $player_info[6]->element)]);
                            else
                                $player_info[6]->element[] = $number;
                            if (count($player_info[6]->element) < 4)
                            {
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `jackpot` = '" . json_encode($player_info[6]) . "' WHERE `user_id` = $user_id;");
                                if (count($player_info[6]->element))
                                    $vk->messages_send($peer_id, 'Вы выбрали следующие числа: ' . implode(', ', $player_info[6]->element), '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 1"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 2"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 3"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 4"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 5"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 6"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отказаться"},"color":"negative"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Поставить 1,000k"},"color":"positive"}]]}');
                                else
                                    $vk->messages_send($peer_id, 'Вы не выбрали ни одного числа', '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 1"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 2"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 3"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 4"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 5"},"color":"secondary"},{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"&#127922; 6"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отказаться"},"color":"negative"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Поставить 1,000k"},"color":"positive"}]]}');
                            } else
                                $vk->messages_send($peer_id, 'Вы не можете выбрать более 3 цифр!', '', '');
                        } else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] поставить 1,000k'):
                        $player_info[6] = json_decode($player_info[6]);
                        $player_info[6]->element = (array)$player_info[6]->element;
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                            'insufficient_funds' => array($player_info[2], 1000),
                            'no_numbers_selected' => array($player_info[6])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            $jackpot = -1000;
                            $dice = rand(1, 6);
                            if (in_array($dice, $player_info[6]->element))
                                switch (count($player_info[6]->element))
                                {
                                    case 1:
                                        $jackpot += 9000;
                                        break;
                                    case 2:
                                        $jackpot += 4000;
                                        break;
                                    case 3:
                                        $jackpot += 2000;
                                        break;
                                }
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` + $jackpot, `jackpot` = NULL WHERE `user_id` = $user_id");
                            $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                            if ($jackpot > -1000)
                                $monopoly->assembling_a_game_card($peer_id, "Вы выиграли выбросив число $dice и окупились на сумму равную: " . number_format($jackpot, 0, ',', ',') . "k\n\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                            else
                                $monopoly->assembling_a_game_card($peer_id, "Вы проиграли выбросив $dice и потеряли 1,000k\n\n" . $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                        } else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] отказаться'):
                        $player_info[6] = json_decode($player_info[6]);
                        $player_info[6]->element = (array)$player_info[6]->element;
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                            'you_cant_lace_a_bet' => array($player_info[6])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `jackpot` = NULL WHERE `user_id` = $user_id");
                            $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                            $monopoly->assembling_a_game_card($peer_id, $monopoly->player_balance($peer_id) . "Бросает кубики: $color", '');
                        } else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] взаимодействие'):
                        $vk->messages_send($peer_id, 'Выберите франшизу', '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Франшиза - Парфюмерия"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Франшиза - Одежда"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Франшиза - Социальные сети"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Франшиза - Напитки"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Франшиза - Авиалинии"},"color":"secondary"}]]}');
                        $vk->messages_send($peer_id, '&#12288;', '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Франшиза - Рестораны"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Франшиза - Отели"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Франшиза - Электроника"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Франшиза - Игровые студии"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Франшиза - Автомобили"},"color":"secondary"}]]}');
                        $command = true;
                        break;
                    case (strpos($text, '[club215797262|@club215797262] франшиза - ') !== false):
                        $franchise = array(
                            array('парфюмерия', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Chanel"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Hugo Boss"},"color":"secondary"}]]}'),
                            array('одежда', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Adidas"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Puma"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Lacoste"},"color":"secondary"}]]}'),
                            array('социальные сети', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - VK"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Facebook"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Twitter"},"color":"secondary"}]]}'),
                            array('напитки', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Coca-Cola"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Pepsi"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Fanta"},"color":"secondary"}]]}'),
                            array('авиалинии', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - American Airlines"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Lufthansa"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - British Airways"},"color":"secondary"}]]}'),
                            array('рестораны', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - McDonald’s"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Burger King"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - KFC"},"color":"secondary"}]]}'),
                            array('отели', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Holiday Inn"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Radisson Blu"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Novotel"},"color":"secondary"}]]}'),
                            array('электроника', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Apple"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Nokia"},"color":"secondary"}]]}'),
                            array('игровые студии', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Rockstar Games"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Rovio Entertainment"},"color":"secondary"}]]}'),
                            array('автомобили', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Mercedes-Benz"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Audi"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Ford"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бренд - Land Rover"},"color":"secondary"}]]}')
                        );
                        $text = mb_substr($text, 38);
                        if (in_array($text, array_column($franchise, 0)))
                            $vk->messages_send($peer_id, 'Выберите бренд', '', $franchise[array_search($text, array_column($franchise, 0))][1]);
                        $command = true;
                        break;
                    case (strpos($text, '[club215797262|@club215797262] бренд - ') !== false):
                        $brand = array('Chanel', 'Hugo Boss', 'Adidas', 'Puma', 'Lacoste', 'VK', 'Facebook', 'Twitter', 'Coca-Cola', 'Pepsi', 'Fanta', 'American Airlines', 'Lufthansa', 'British Airways', 'McDonald’s', 'Burger King', 'KFC', 'Holiday Inn', 'Radisson Blu', 'Novotel', 'Apple', 'Nokia');
                        $text = mb_substr($vk->data->object->message->text, 35);
                        $field_info = $mysqli->query("SELECT * FROM `monopoly`.`information_about_fields` WHERE `name` = '$text'");
                        $field_info = $field_info->fetch_row();
                        if (in_array($text, $brand))
                        {
                            $field_info[3] = json_decode($field_info[3]);
                            $vk->messages_send($peer_id, "Информация о поле\nОдна звезда: " . number_format($field_info[3]->rent[0], 0, ',', ',') . "k\nДве звезды: " . number_format($field_info[3]->rent[1], 0, ',', ',') . "k\nТри звезды: " . number_format($field_info[3]->rent[2], 0, ',', ',') . "k\nЧетыре звезды: " . number_format($field_info[3]->rent[3], 0, ',', ',') . "k\nПять звёзд: " . number_format($field_info[3]->rent[4], 0, ',', ',') . "k\nБольшая звезда: " . number_format($field_info[3]->rent[5], 0, ',', ',') . "k\n\nСтоимость поля: " . number_format($field_info[4], 0, ',', ',') . "k\nЗалог поля: " . number_format($field_info[5], 0, ',', ',') . "k\nВыкуп поля: " . number_format($field_info[6], 0, ',', ',') . "k\nПокупка филиала: " . number_format($field_info[7], 0, ',', ',') . 'k', '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Построить филиал - ' . $text . '"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Продать - ' . $text . '"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Выкупить - ' . $text . '"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Предложить - ' . $text . '"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Запросить - ' . $text . '"},"color":"secondary"}]]}');
                        }
                        $brand = array('Mercedes-Benz', 'Audi', 'Ford', 'Land Rover');
                        if (in_array($text, $brand))
                            $vk->messages_send($peer_id, "Информация о поле\nСумма аренды зависит от количества полей автомобильных франшиз которыми вы владеете.\nОдно поле: 250k\nДва поля: 500k\nТри поля: 1,000k\nЧетыре поля: 2,000k\n\nСтоимость поля: " . number_format($field_info[4], 0, ',', ',') . "k\nЗалог поля: " . number_format($field_info[5], 0, ',', ',') . "k\nВыкуп поля: " . number_format($field_info[6], 0, ',', ',') . 'k', '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Продать - ' . $text . '"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Выкупить - ' . $text . '"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Предложить - ' . $text . '"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Запросить - ' . $text . '"},"color":"secondary"}]]}');
                        $brand = array('Rockstar Games', 'Rovio');
                        if (in_array($text, $brand))
                            $vk->messages_send($peer_id, "Информация о поле\nСумма аренды зависит от количества игровых студий которыми вы владеете и от суммы чисел выпавшей на кубиках.\nОдно поле: ×100\nДва поля: ×250\n\nСтоимость поля: " . number_format($field_info[4], 0, ',', ',') . "k\nЗалог поля: " . number_format($field_info[5], 0, ',', ',') . "k\nВыкуп поля: " . number_format($field_info[6], 0, ',', ',') . 'k', '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Продать - ' . $text . '"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Выкупить - ' . $text . '"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Предложить - ' . $text . '"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Запросить - ' . $text . '"},"color":"secondary"}]]}');
                        $command = true;
                        break;
                    case (strpos($text, '[club215797262|@club215797262] построить филиал - ') !== false):
                        $brand = array('Chanel', 'Hugo Boss', 'Mercedes-Benz', 'Adidas', 'Puma', 'Lacoste', 'VK', 'Rockstar Games', 'Facebook', 'Twitter', 'Audi', 'Coca-Cola', 'Pepsi', 'Fanta', 'American Airlines', 'Lufthansa', 'British Airways', 'Ford', 'McDonald’s', 'Burger King', 'Rovio', 'KFC', 'Holiday Inn', 'Radisson Blu', 'Novotel', 'Land Rover', 'Apple', 'Nokia');
                        $text = mb_substr($vk->data->object->message->text, 46);
                        if (in_array($text, $brand))
                        {
                            $map_info = $mysqli->query("SELECT `franchise` FROM `monopoly`.`information_about_fields` WHERE `name` = '$text';");
                            $map_info = $map_info->fetch_row();
                            $map_info = $mysqli->query("SELECT `ID` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `recruiter` != $player_info[1] AND `franchise` = '$map_info[0]';");
                            $map_info = $map_info->fetch_row();
                            $field_info = $mysqli->query("SELECT `purchase_of_a_branch`, `level` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `recruiter` = $player_info[1] AND `name` = '$text';");
                            $field_info = $field_info->fetch_row();
                            $checking_game_conditions = $monopoly->checking_game_conditions(array(
                                'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                                'you_dont_own_all_fields_of_this_color' => array($map_info),
                                'field_contains_a_maximum_of_branches' => array($field_info[1]),
                                'impossible_to_build_a_branch' => array($building_a_branch[0]),
                                'insufficient_funds' => array($player_info[2], $field_info[0])
                            ));

                            if (!is_array($checking_game_conditions))
                            {
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `level` = `level` + 1 WHERE `name` = '$text';");
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` - $field_info[0] WHERE `user_id` = $user_id;");
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 0 WHERE `ID` = 4;");
                                $color = $monopoly->current_move($peer_id);
                                $monopoly->assembling_a_game_card($peer_id, "Филиал построен\nБросает кубики: $color[1]\n" . $monopoly->player_balance($peer_id), '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бросить кубики"},"color":"positive"}]]}');
                            } else
                                $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        }
                        $command = true;
                        break;
                    case (strpos($text, '[club215797262|@club215797262] продать - ') !== false):
                        $brand = array('Chanel', 'Hugo Boss', 'Mercedes-Benz', 'Adidas', 'Puma', 'Lacoste', 'VK', 'Rockstar Games', 'Facebook', 'Twitter', 'Audi', 'Coca-Cola', 'Pepsi', 'Fanta', 'American Airlines', 'Lufthansa', 'British Airways', 'Ford', 'McDonald’s', 'Burger King', 'Rovio', 'KFC', 'Holiday Inn', 'Radisson Blu', 'Novotel', 'Land Rover', 'Apple', 'Nokia');
                        $text = mb_substr($vk->data->object->message->text, 37);
                        if (in_array($text, $brand))
                        {
                            $field_info = $mysqli->query("SELECT `recruiter`, `level`, `purchase_of_a_branch`, `field_deposit` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `recruiter` = $player_info[1] AND `name` = '$text';");
                            $field_info = $field_info->fetch_row();
                            $branches_info = $mysqli->query("SELECT `ID` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `level` > 0 AND `franchise` = (SELECT `franchise` FROM `monopoly`.`information_about_fields` WHERE `name` = '$text')");
                            $branches_info = $branches_info->fetch_row();
                            $checking_game_conditions = $monopoly->checking_game_conditions(array(
                                'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                                'field_does_not_belong_to_you' => array($player_info[1], $field_info[0]),
                                'field_has_already_been_laid' => array($field_info[1]),
                                'sell_the_branches_first' => array($branches_info, $field_info[1])
                            ));

                            if (!is_array($checking_game_conditions))
                            {
                                if ($field_info[1] > 0)
                                {
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `level` = `level` - 1 WHERE `name` = '$text';");
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` + $field_info[2] WHERE `user_id` = $user_id;");
                                }
                                else
                                {
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `level` = -15 WHERE `name` = '$text';");
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` + $field_info[3] WHERE `user_id` = $user_id;");
                                }
                                $color = $monopoly->current_move($peer_id);
                                $monopoly->assembling_a_game_card($peer_id, "Продажа состоялась\nБросает кубики: $color[1]\n" . $monopoly->player_balance($peer_id), '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бросить кубики"},"color":"positive"}]]}');
                            }
                            else
                                $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        }
                        $command = true;
                        break;
                    case (strpos($text, '[club215797262|@club215797262] выкупить - ') !== false):
                        $brand = array('Chanel', 'Hugo Boss', 'Mercedes-Benz', 'Adidas', 'Puma', 'Lacoste', 'VK', 'Rockstar Games', 'Facebook', 'Twitter', 'Audi', 'Coca-Cola', 'Pepsi', 'Fanta', 'American Airlines', 'Lufthansa', 'British Airways', 'Ford', 'McDonald’s', 'Burger King', 'Rovio', 'KFC', 'Holiday Inn', 'Radisson Blu', 'Novotel', 'Land Rover', 'Apple', 'Nokia');
                        $text = mb_substr($vk->data->object->message->text, 38);
                        if (in_array($text, $brand))
                        {
                            $field_info = $mysqli->query("SELECT `recruiter`, `level`, `field_redemption` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `recruiter` = $player_info[1] AND `name` = '$text';");
                            $field_info = $field_info->fetch_row();
                            $checking_game_conditions = $monopoly->checking_game_conditions(array(
                                'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                                'field_does_not_belong_to_you' => array($player_info[1], $field_info[0]),
                                'field_is_not_laid' => array($field_info[1])
                            ));

                            if (!is_array($checking_game_conditions))
                            {
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `level` = 0 WHERE `name` = '$text';");
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` - $field_info[2] WHERE `user_id` = $user_id;");
                                $color = $monopoly->current_move($peer_id);
                                $monopoly->assembling_a_game_card($peer_id, "Поле выкуплено\nБросает кубики: $color[1]\n" . $monopoly->player_balance($peer_id), '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бросить кубики"},"color":"positive"}]]}');
                            }
                            else
                                $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        }
                        $command = true;
                        break;
                    case (strpos($text, '[club215797262|@club215797262] предложить - ') !== false):
                        $brand = array('Chanel', 'Hugo Boss', 'Mercedes-Benz', 'Adidas', 'Puma', 'Lacoste', 'VK', 'Rockstar Games', 'Facebook', 'Twitter', 'Audi', 'Coca-Cola', 'Pepsi', 'Fanta', 'American Airlines', 'Lufthansa', 'British Airways', 'Ford', 'McDonald’s', 'Burger King', 'Rovio', 'KFC', 'Holiday Inn', 'Radisson Blu', 'Novotel', 'Land Rover', 'Apple', 'Nokia');
                        $text = mb_substr($vk->data->object->message->text, 40);
                        if (in_array($text, $brand))
                        {
                            $field_info = $mysqli->query("SELECT `recruiter`, `level` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `recruiter` = $player_info[1] AND `name` = '$text';");
                            $field_info = $field_info->fetch_row();
                            $branches_info = $mysqli->query("SELECT `ID` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `level` > 0 AND `franchise` = (SELECT `franchise` FROM `monopoly`.`information_about_fields` WHERE `name` = '$text')");
                            $branches_info = $branches_info->fetch_row();
                            $checking_game_conditions = $monopoly->checking_game_conditions(array(
                                'field_does_not_belong_to_you' => array($player_info[1], $field_info[0]),
                                'sell_the_branches_first' => array($branches_info, $field_info[1])
                            ));

                            if (!is_array($checking_game_conditions))
                            {
                                $deal = json_decode($player_info[7], true);
                                if (in_array($text, $deal['give']))
                                    unset($deal['give'][array_search($text, $deal['give'])]);
                                else
                                    $deal['give'][] = $text;
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `deal` = '" . json_encode($deal) . "' WHERE `user_id` = $user_id");

                                if ($deal['money_give'])
                                    $deal['give'][] = number_format($deal['money_give'], 0, ',', ',') . 'k';
                                elseif ($deal['money_get'])
                                    $deal['get'][] = number_format($deal['money_get'], 0, ',', ',') . 'k';
                                $monopoly->assembling_a_game_card($peer_id, "Текущая сделка\nВы предоставляете: " . implode('; ', $deal['give']) . "\nВы получаете: " . implode('; ', $deal['get']) . "\n\nЧтобы предложить или запросить денежные средства напишите:\n[club215797262|@club215797262] Предложить - 1000\n[club215797262|@club215797262] Запросить - 1000", '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отменить сделку"},"color":"negative"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Предложить сделку игроку"},"color":"positive"}]]}');
                            }
                            else
                                $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        }
                        else
                        {
                            $deal = json_decode($player_info[7], true);
                            $deal['money_give'] = abs(intval($text));
                            $deal['money_get'] = 0;
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `deal` = '" . json_encode($deal) . "' WHERE `user_id` = $user_id");

                            if ($deal['money_give'])
                                $deal['give'][] = number_format($deal['money_give'], 0, ',', ',') . 'k';
                            $monopoly->assembling_a_game_card($peer_id, "Текущая сделка\nВы предоставляете: " . implode('; ', $deal['give']) . "\nВы получаете: " . implode('; ', $deal['get']) . "\n\nЧтобы предложить или запросить денежные средства напишите:\n[club215797262|@club215797262] Предложить - 1000\n[club215797262|@club215797262] Запросить - 1000", '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отменить сделку"},"color":"negative"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Предложить сделку игроку"},"color":"positive"}]]}');
                        }
                        $command = true;
                        break;
                    case (strpos($text, '[club215797262|@club215797262] запросить - ') !== false):
                        $brand = array('Chanel', 'Hugo Boss', 'Mercedes-Benz', 'Adidas', 'Puma', 'Lacoste', 'VK', 'Rockstar Games', 'Facebook', 'Twitter', 'Audi', 'Coca-Cola', 'Pepsi', 'Fanta', 'American Airlines', 'Lufthansa', 'British Airways', 'Ford', 'McDonald’s', 'Burger King', 'Rovio', 'KFC', 'Holiday Inn', 'Radisson Blu', 'Novotel', 'Land Rover', 'Apple', 'Nokia');
                        $text = mb_substr($vk->data->object->message->text, 39);
                        if (in_array($text, $brand))
                        {
                            $field_info = $mysqli->query("SELECT `level` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `recruiter` = $player_info[1] AND `name` = '$text';");
                            $field_info = $field_info->fetch_row();
                            $branches_info = $mysqli->query("SELECT `ID` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `level` > 0 AND `franchise` = (SELECT `franchise` FROM `monopoly`.`information_about_fields` WHERE `name` = '$text')");
                            $branches_info = $branches_info->fetch_row();
                            $checking_game_conditions = $monopoly->checking_game_conditions(array(
                                'sell_the_branches_first' => array($branches_info, $field_info[0])
                            ));

                            if (!is_array($checking_game_conditions))
                            {
                                $deal = json_decode($player_info[7], true);
                                if (in_array($text, $deal['get']))
                                    unset($deal['get'][array_search($text, $deal['get'])]);
                                else
                                    $deal['get'][] = $text;
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `deal` = '" . json_encode($deal) . "' WHERE `user_id` = $user_id");

                                if ($deal['money_give'])
                                    $deal['give'][] = number_format($deal['money_give'], 0, ',', ',') . 'k';
                                elseif ($deal['money_get'])
                                    $deal['get'][] = number_format($deal['money_get'], 0, ',', ',') . 'k';
                                $monopoly->assembling_a_game_card($peer_id, "Текущая сделка\nВы предоставляете: " . implode('; ', $deal['give']) . "\nВы получаете: " . implode('; ', $deal['get']) . "\n\nЧтобы предложить или запросить денежные средства напишите:\n[club215797262|@club215797262] Предложить - 1000\n[club215797262|@club215797262] Запросить - 1000", '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отменить сделку"},"color":"negative"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Предложить сделку игроку"},"color":"positive"}]]}');
                            }
                            else
                                $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        }
                        else
                        {
                            $deal = json_decode($player_info[7], true);
                            $deal['money_give'] = 0;
                            $deal['money_get'] = abs(intval($text));
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `deal` = '" . json_encode($deal) . "' WHERE `user_id` = $user_id");

                            if ($deal['money_get'])
                                $deal['get'][] = number_format($deal['money_get'], 0, ',', ',') . 'k';
                            $monopoly->assembling_a_game_card($peer_id, "Текущая сделка\nВы предоставляете: " . implode('; ', $deal['give']) . "\nВы получаете: " . implode('; ', $deal['get']) . "\n\nЧтобы предложить или запросить денежные средства напишите:\n[club215797262|@club215797262] Предложить - 1000\n[club215797262|@club215797262] Запросить - 1000", '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отменить сделку"},"color":"negative"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Предложить сделку игроку"},"color":"positive"}]]}');
                        }
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] отменить сделку'):
                        $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `deal` = '{\"color\":null,\"money_give\":0,\"money_get\":0,\"give\":[],\"get\":[]}' WHERE `user_id` = $user_id");
                        $color = $monopoly->current_move($peer_id);
                        $monopoly->assembling_a_game_card($peer_id, "{$monopoly->player_balance($peer_id)}Бросает кубики: $color[1]", '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] предложить сделку игроку'):
                        $keyboard = array();
                        $set_color = array(1 => 'красным', 'синим', 'зелёным', 'фиолетовым');
                        $player_info = $mysqli->query("SELECT `color` FROM `monopoly`.`{$peer_id}_players` WHERE `user_id` != $user_id");
                        while ($row = mysqli_fetch_row($player_info))
                            $keyboard[] = '[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"С ' . $set_color[$row[0]] . ' цветом фишек"},"color":"secondary"}]';
                        $keyboard = '{"inline":true,"buttons":[' . implode(',', $keyboard) . ']}';
                        $vk->messages_send($peer_id, 'Выберите игрока которому хотите предложить сделку.', '', $keyboard);
                        $command = true;
                        break;
                    case (strpos($text, '[club215797262|@club215797262] с ') !== false):
                        $text = mb_substr($vk->data->object->message->text, 29);
                        $set_color = array(1 => 'красным цветом фишек', 'синим цветом фишек', 'зелёным цветом фишек', 'фиолетовым цветом фишек');
                        $color = array_search($text, $set_color);
                        if ($color)
                        {
                            $checking_game_conditions = $monopoly->checking_game_conditions(array(
                                'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                                'you_cant_send_the_contract_to_yourself' => array($player_info[1], $color)
                            ));

                            if (!is_array($checking_game_conditions))
                            {
                                $deal = json_decode($player_info[7], true);
                                $deal['color'] = $color;
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `deal` = '" . json_encode($deal) . "' WHERE `user_id` = $user_id");

                                if ($deal['money_give'])
                                    $deal['give'][] = $deal['money_give'];
                                elseif ($deal['money_get'])
                                    $deal['get'][] = $deal['money_get'];
                                $id = $mysqli->query("SELECT `user_id` FROM `monopoly`.`{$peer_id}_players` WHERE `color` = $color");
                                $id = $id->fetch_row();
                                $monopoly->assembling_a_game_card($peer_id, "[id$id[0]|Вам] предлагают сделку\nВы предоставляете: " . implode('; ', $deal['get']) . "\nВы получаете: " . implode('; ', $deal['give']), '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Принять сделку"},"color":"positive"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отказаться от сделки"},"color":"negative"}]]}');
                            }
                            else
                                $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        }
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] принять сделку'):
                        $deal = $mysqli->query("SELECT `deal` FROM `monopoly`.`{$peer_id}_players` WHERE `color` = $current_move[0]");
                        $deal = $deal->fetch_row();
                        $deal = json_decode($deal[0], true);
                        if ($deal['money_give'])
                        {
                            $money_user = $mysqli->query("SELECT `money` FROM `monopoly`.`{$peer_id}_players` WHERE `color` = '$current_move[0]'");
                            $money_user = $money_user->fetch_row();
                            $money = $deal['money_give'];
                        }
                        else
                        {
                            $money_user[0] = $player_info[2];
                            $money = $deal['money_get'];
                        }
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($deal['color'], $player_info[1]),
                            'contract_was_drawn_up_incorrectly' => array($peer_id, $deal),
                            'insufficient_funds' => array($money_user[0], $money)
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            foreach ($deal['give'] as $name)
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `recruiter` = {$deal['color']} WHERE `name` = '$name'");
                            foreach ($deal['get'] as $name)
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `recruiter` = $current_move[0] WHERE `name` = '$name'");
                            if ($deal['money_give'])
                            {
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` + {$deal['money_give']} WHERE `color` = {$deal['color']}");
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` - {$deal['money_give']} WHERE `color` = $current_move[0]");
                            }
                            if ($deal['money_get'])
                            {
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` + {$deal['money_get']} WHERE `color` = $current_move[0]");
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` - {$deal['money_get']} WHERE `color` = {$deal['color']}");
                            }
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `deal` = '{\"color\":null,\"money_give\":0,\"money_get\":0,\"give\":[],\"get\":[]}' WHERE `color` = $current_move[0]");
                            $color = $monopoly->current_move($peer_id);
                            $monopoly->assembling_a_game_card($peer_id, "Сделка совершена\n\n{$monopoly->player_balance($peer_id)}Бросает кубики: $color[1]", '');
                        }
                        else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] отказаться от сделки'):
                        $deal = $mysqli->query("SELECT `deal` FROM `monopoly`.`{$peer_id}_players` WHERE `color` = $current_move[0]");
                        $deal = $deal->fetch_row();
                        $deal = json_decode($deal[0], true);
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($deal['color'], $player_info[1])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `deal` = '{\"color\":null,\"money_give\":0,\"money_get\":0,\"give\":[],\"get\":[]}' WHERE `color` = $current_move[0]");
                            $color = $monopoly->current_move($peer_id);
                            $monopoly->assembling_a_game_card($peer_id, "Ваше преложение сделки было отклонено\n\n{$monopoly->player_balance($peer_id)}Бросает кубики: $color[1]", '');
                        }
                        else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] сдаться'):
                        $mysqli->query("DELETE FROM `monopoly`.`{$peer_id}_players` WHERE `user_id` = $user_id");
                        $mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `recruiter` = 0, `level` = 0 WHERE `recruiter` = $player_info[1]");
                        $count_players = $mysqli->query("SELECT count(*) FROM `monopoly`.`{$peer_id}_players`");
                        $count_players = $count_players->fetch_row();
                        if ($count_players[0] > 1)
                            if ($player_info[1] == $current_move[0])
                            {
                                $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                $monopoly->assembling_a_game_card($peer_id, "Вы закончили игру и ход передаётя другому игроку\n\n{$monopoly->player_balance($peer_id)}Бросает кубики: $color", '');
                            }
                            else
                                $vk->messages_send($peer_id, 'Вы покидаете игру', '', '');
                        else
                        {
                            $set_color = array(1 => 'Красный', 'Синий', 'Зелёный', 'Филетовый');
                            $color = $mysqli->query("SELECT `user_id`, `color` FROM `monopoly`.`{$peer_id}_players`");
                            $color = $color->fetch_row();
                            $vk->messages_send($peer_id, "Выиграл [id$color[0]|{$set_color[$color[1]]}]", '', '');
                            $mysqli->query("DROP TABLE `monopoly`.`{$peer_id}_game_parameters`");
                            $mysqli->query("DROP TABLE `monopoly`.`{$peer_id}_map`");
                            $mysqli->query("DROP TABLE `monopoly`.`{$peer_id}_players`");
                        }
                        $command = true;
                        break;
                }
            }
            else
            {
                $current_move = $mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 7");
                $current_move = $current_move->fetch_row();
                $count_players = $mysqli->query("SELECT count(*) FROM `monopoly`.`{$peer_id}_players` WHERE `refused_the_auction` = 0;");
                $count_players = $count_players->fetch_row();
                switch (true)
                {
                    case ($text == '[club215797262|@club215797262] выкупить поле'):
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1]),
                            'insufficient_funds' => array($player_info[2], $cost_of_the_field_at_auction[0])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            if ($count_players[0] == 1)
                            {
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `recruiter` = '$player_info[1]' WHERE `ID` = $field_participating_in_auction[0];");
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `money` = `money` - $cost_of_the_field_at_auction[0] WHERE `user_id` = $user_id;");
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `refused_the_auction` = 0;");
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 0 WHERE `ID` > 4 AND `ID` < 8;");

                                $vk->messages_send($peer_id, "$player_info[0] | $player_info[1] | $player_info[2]", '', '');
                                $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                $monopoly->assembling_a_game_card($peer_id, "Поле выкуплено\nБросает кубики: $color\n" . $monopoly->player_balance($peer_id), '');
                            }
                        } else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] повысить ставку'):
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            if ($count_players[0] == 1)
                                $vk->messages_send($peer_id, "Поле разыгрывается на аукционе!\nТекущая стоимость: " . number_format($cost_of_the_field_at_auction[0], 0, ',', ',') . "k\nВыбор делает: " . $monopoly->print_color($current_move[0]), '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Выкупить поле"},"color":"positive"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отказаться от участия"},"color":"negative"}]]}');
                            else
                            {
                                $cost_of_the_field_at_auction[0] += 100;
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = '$cost_of_the_field_at_auction[0]' WHERE `ID` = 6;");
                                $current_move = $mysqli->query("SELECT `color` FROM `monopoly`.`{$peer_id}_players` WHERE `refused_the_auction` = 0 AND `user_id` != '$user_id';");
                                $current_move = $current_move->fetch_row();
                                $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = '$current_move[0]' WHERE `ID` = 7;");
                                $vk->messages_send($peer_id, "Поле разыгрывается на аукционе!\nТекущая стоимость: " . number_format($cost_of_the_field_at_auction[0], 0, ',', ',') . "k\nВыбор делает: " . $monopoly->print_color($current_move[0]), '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Повысить ставку"},"color":"positive"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отказаться от участия"},"color":"negative"}]]}');
                            }
                        } else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                    case ($text == '[club215797262|@club215797262] отказаться от участия'):
                        $checking_game_conditions = $monopoly->checking_game_conditions(array(
                            'checking_the_current_progress' => array($current_move[0], $player_info[1])
                        ));

                        if (!is_array($checking_game_conditions))
                        {
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `refused_the_auction` = '1' WHERE `user_id` = $user_id;");
                            $current_move = $mysqli->query("SELECT `color` FROM `monopoly`.`{$peer_id}_players` WHERE `refused_the_auction` = 0;");
                            $current_move = $current_move->fetch_row();
                            $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = '$current_move[0]' WHERE `ID` = 7;");
                            $count_players = $mysqli->query("SELECT count(*) FROM `monopoly`.`{$peer_id}_players` WHERE `refused_the_auction` = 0;");
                            $count_players = $count_players->fetch_row();
                            switch ($count_players[0])
                            {
                                case 1:
                                    $vk->messages_send($peer_id, "Поле разыгрывается на аукционе!\nТекущая стоимость: " . number_format($cost_of_the_field_at_auction[0], 0, ',', ',') . "k\nВыбор делает: " . $monopoly->print_color($current_move[0]), '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Выкупить поле"},"color":"positive"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отказаться от участия"},"color":"negative"}]]}');
                                    break;
                                case 0:
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `refused_the_auction` = 0;");
                                    $mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 0 WHERE `ID` > 4 AND `ID` < 8;");
                                    $color = $monopoly->pass_the_move($peer_id, $type_game[0]);
                                    $monopoly->assembling_a_game_card($peer_id, "Все игроки отказались от участия в аукционе\nБросает кубики: $color\n" . $monopoly->player_balance($peer_id), '');
                                    break;
                                default:
                                    $vk->messages_send($peer_id, "Поле разыгрывается на аукционе!\nТекущая стоимость: " . number_format($cost_of_the_field_at_auction[0], 0, ',', ',') . "k\nВыбор делает: " . $monopoly->print_color($current_move[0]), '', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Повысить ставку"},"color":"positive"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Отказаться от участия"},"color":"negative"}]]}');
                            }
                        }
                        else
                            $vk->messages_send($peer_id, $checking_game_conditions[0], '', '');
                        $command = true;
                        break;
                }
            }
        }
    }
}