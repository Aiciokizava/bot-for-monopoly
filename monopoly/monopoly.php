<?php

class Monopoly
{
    public $vk;
    public $mysqli;

    // Прорисовка игрового стола
    public function assembling_a_game_card($peer_id, $message, $keyboard)
    {
        $image = imagecreatefromjpeg(__DIR__ . '/resources/map.jpg');

        $color = imagecolorallocate($image, 48, 45, 46);
        $font = __DIR__ . '/resources/Heebo-Light.ttf';
        $coordinates = $this->mysqli->query("SELECT `recruiter`, `field_cost`, `level`, `rent`, `coordinates_for_text`, `franchise`, `ID` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `franchise` IS NOT NULL");
        while ($row = mysqli_fetch_row($coordinates))
        {
            $row[4] = json_decode($row[4]);
            if ($row[0])
            {
                $row[3] = json_decode($row[3]);
                $money = number_format($row[3]->rent[$row[2]], 0, ',', ',') . 'k';
            }
            else
                $money = number_format($row[1], 0, ',', ',') . 'k';
            if ($row[5] == 'cars' and $money != '2,000k')
            {
                $money_cars = array(1 => 250, 500, 1000, 2000);
                $money = $this->mysqli->query("SELECT COUNT(*) FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `franchise` = 'cars' AND `recruiter` = (SELECT `recruiter` FROM `monopoly`.`{$peer_id}_map` WHERE `ID` = '$row[6]')");
                $money = $money->fetch_row();
                $money = number_format($money_cars[$money[0]], 0, ',', ',') . 'k';
            }
            elseif ($row[5] == 'game studios' and $money != '1,500k')
            {
                $money_game_studios = array(1 => 100, 250);
                $money = $this->mysqli->query("SELECT COUNT(*) FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `franchise` = 'game studios' AND `recruiter` = (SELECT `recruiter` FROM `monopoly`.`{$peer_id}_map` WHERE `ID` = '$row[6]')");
                $money = $money->fetch_row();
                $money = number_format($money_game_studios[$money[0]], 0, ',', ',') . 'X';
            }

            $dimensions = imagettfbbox(14, 0, $font, $money);
            if ($row[6] < 10 or ($row[6] > 21 and $row[6] < 31))
            {
                $row[4]->x = $row[4]->x + (69 - $dimensions[4]) / 2;
                imagefttext($image, 14, 0, $row[4]->x, $row[4]->y, $color, $font, $money);
            }
            elseif ($row[6] > 11 and $row[6] < 21)
            {
                $row[4]->y = $row[4]->y + (69 - $dimensions[4]) / 2;
                imagefttext($image, 14, 270, $row[4]->x, $row[4]->y, $color, $font, $money);
            }
            else
            {
                $row[4]->y = $row[4]->y - (69 - $dimensions[4]) / 2;
                imagefttext($image, 14, 90, $row[4]->x, $row[4]->y, $color, $font, $money);
            }
        }

        $set_color = array(1 => 'field_red', 'field_blue', 'field_green', 'field_purple');
        $coordinates = $this->mysqli->query("SELECT `recruiter`, `coordinates_for_field_colors`, `level` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `recruiter` != 0;");
        while ($row = mysqli_fetch_row($coordinates))
        {
            $json = json_decode($row[1]);
            if ($row[2] < 0)
                $this->setting_up_the_field($image, $set_color[$row[0]] . '_lock', 0, $json->x, $json->y, $json->dst_width, $json->dst_height, 1, 1);
            else
                $this->setting_up_the_field($image, $set_color[$row[0]], 0, $json->x, $json->y, $json->dst_width, $json->dst_height, 1, 1);
        }

        $playing_fields = array(
            // Первый ряд
            array('chanel', 90, 166, 50, 42, 80, 98, 153),
            array('hugo_boss', 90, 306, 43, 42, 95, 312, 850),
            array('mercedes', 90, 437, 64, 53, 54, 63, 64),
            array('adidas', 90, 511, 50, 43, 80, 204, 302),
            array('puma', 90, 646, 42, 43, 95, 149, 300),
            array('lacoste', 90, 718, 43, 43, 95, 144, 299),
            // Второй ряд
            array('vk', 0, 796, 164, 82, 47, 128, 76),
            array('rockstar_games', 0, 810, 231, 56, 52, 216, 199),
            array('facebook', 0, 790, 315, 95, 19, 433, 84),
            array('twitter', 0, 806, 370, 65, 50, 250, 203),
            array('audi', 0, 787, 446, 100, 35, 193, 67),
            array('coca_cola', 0, 789, 516, 100, 35, 200, 65),
            array('pepsi', 0, 789, 654, 100, 35, 512, 158),
            array('fanta', 0, 808, 715, 60, 50, 371, 300),
            // Третий ряд
            array('american_airlines', 90, 730, 788, 20, 100, 45, 300),
            array('lufthansa', 90, 592, 788, 20, 100, 92, 538),
            array('british_airways', 90, 523, 788, 20, 100, 159, 1032),
            array('ford', 90, 444, 788, 38, 100, 112, 300),
            array('mcdonalds', 90, 373, 809, 44, 58, 239, 273),
            array('burger_king', 90, 305, 815, 42, 45, 620, 569),
            array('rovio', 90, 244, 790, 24, 95, 49, 200),
            array('kfc', 90, 164, 815, 46, 46, 310, 310),
            // Четвёртый ряд
            array('holiday_inn', 0, 57, 718, 68, 48, 217, 152),
            array('radisson_blu', 0, 38, 658, 104, 30, 398, 119),
            array('novotel', 0, 38, 510, 103, 42, 165, 65),
            array('land_rover', 0, 42, 441, 95, 46, 175, 92),
            array('apple', 0, 69, 303, 40, 46, 136, 162),
            array('nokia', 0, 40, 180, 102, 17, 313, 53)
        );
        for ($i = 0; $i < 28; $i++)
            $this->setting_up_the_field($image, $playing_fields[$i][0], $playing_fields[$i][1], $playing_fields[$i][2], $playing_fields[$i][3], $playing_fields[$i][4], $playing_fields[$i][5], $playing_fields[$i][6], $playing_fields[$i][7]);

        $players = $this->mysqli->query("SELECT DISTINCT cell FROM `monopoly`.`{$peer_id}_players` ORDER BY `{$peer_id}_players`.`cell` ASC");
        while ($row_first = mysqli_fetch_row($players))
        {
            $players_on_coordinates = $this->mysqli->query("SELECT `color` FROM `monopoly`.`{$peer_id}_players` WHERE `cell` = $row_first[0]");
            $colors = array();
            while ($row_second = mysqli_fetch_row($players_on_coordinates))
                $colors[] = $row_second[0];
            $count = count($colors);
            $map = $this->mysqli->query("SELECT `coordinates_for_chips` FROM `monopoly`.`information_about_fields` WHERE `ID` = $row_first[0]");
            $map = $map->fetch_row();
            $json_coordinates = json_decode($map[0]);
            $dst_x = $json_coordinates->coordinates[$count - 1]->x;
            $dst_y = $json_coordinates->coordinates[$count - 1]->y;
            for ($i = 0; $i < $count; $i++)
            {
                switch ($row_first[0])
                {
                    case 0:case 21:case 31:
                    $this->placement_of_chips($image, $colors[$i], $dst_x, $dst_y, 36, 36);
                    switch ($count)
                    {
                        case 2:
                            $dst_x += 45;
                            break;
                        case 3:
                            if ($i == 0)
                            {
                                $dst_x -= 22;
                                $dst_y += 45;
                            }
                            else
                                $dst_x += 45;
                            break;
                        case 4:
                            if ($i % 2)
                            {
                                $dst_x -= 45;
                                $dst_y += 45;
                            }
                            else
                                $dst_x += 45;
                            break;
                    }
                    break;
                    case 10:case 11:
                    if ($count == 1)
                        $this->placement_of_chips($image, $colors[$i], $dst_x, $dst_y, 36, 36);
                    else
                        $this->placement_of_chips($image, $colors[$i], $dst_x, $dst_y, 20, 20);
                    switch ($count)
                    {
                        case 2:
                            $dst_x += 25;
                            break;
                        case 3:
                            if ($i == 0)
                            {
                                $dst_x -= 12;
                                $dst_y += 25;
                            }
                            else
                                $dst_x += 25;
                            break;
                        case 4:
                            if ($i % 2)
                            {
                                $dst_x -= 25;
                                $dst_y += 25;
                            }
                            else
                                $dst_x += 25;
                            break;
                    }
                    break;
                    default:
                        if ($count < 3)
                            $this->placement_of_chips($image, $colors[$i], $dst_x, $dst_y, 36, 36);
                        else
                            $this->placement_of_chips($image, $colors[$i], $dst_x, $dst_y, 26, 26);
                        if ($row_first[0] < 11 or ($row_first[0] > 22 and $row_first[0] < 32))
                        {
                            if ($count < 3)
                                $dst_y += 45;
                            else
                                $dst_y += 30;
                        }
                        else
                        {
                            if ($count < 3)
                                $dst_x += 45;
                            else
                                $dst_x += 30;
                        }
                        break;
                }
            }
        }

        $coordinates = $this->mysqli->query("SELECT `ID`, `level`, `coordinates_for_stars` FROM `monopoly`.`{$peer_id}_map` INNER JOIN `monopoly`.`information_about_fields` using(ID, name) WHERE `recruiter` != 0;");
        while ($row = mysqli_fetch_row($coordinates))
        {
            $json = json_decode($row[2]);
            if ($row[1] > 0)
            {
                $step = array(1 => 0, 9, 17, 25, -6);
                if ($row[0] < 11 or (22 < $row[0] and 32 > $row[0]))
                    $json->x += $step[$row[1]];
                else
                    $json->y += $step[$row[1]];
                if ($row[1] < 5)
                    for ($i = 0; $i < $row[1]; $i++)
                    {
                        $this->setting_up_the_field($image, 'silver_star', 0, $json->x, $json->y, 16, 16, 18, 18);
                        if ($row[0] < 11 or (22 < $row[0] and 32 > $row[0]))
                            $json->x -= 16;
                        else
                            $json->y -= 16;
                    }
                else
                {
                    if ($row[0] < 11 or (22 < $row[0] and 32 > $row[0]))
                        $json->y -= 7;
                    elseif ($row[0] > 32)
                        $json->x -= 6;
                    else
                        $json->x -= 7;
                    $this->setting_up_the_field($image, 'gold_star', 0, $json->x, $json->y, 28, 29, 33, 35);
                }
            }
            elseif ($row[1] < 0)
                $this->setting_up_the_field($image, $row[1] * -1 . '_lock', 0, $json->x - 16, $json->y - 5, 50, 30, 125, 73);
        }

        imagejpeg($image, __DIR__ . "/tables/$peer_id.jpg", 100);
        imagedestroy($image);

        $photos = $this->vk->photos_get_messages_upload_server('');
        $photos = $this->vk->photos_upload_server($photos->response->upload_url, __DIR__ . '/tables/' . $peer_id . '.jpg');
        $photos = $this->vk->photos_save_messages_photo($photos->photo, $photos->server, $photos->hash);

        if (!$keyboard)
            $keyboard = '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бросить кубики"},"color":"positive"}]]}';
        $current_move = $this->mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 2");
        $current_move = $current_move->fetch_row();
        $arrest = $this->mysqli->query("SELECT `arrest` FROM `monopoly`.`{$peer_id}_players` WHERE `color` = $current_move[0]");
        $arrest = $arrest->fetch_row();
        if ($arrest[0] and $arrest[0] < 4)
            $keyboard = '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Взаимодействие"},"color":"secondary"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Выйти из тюрьмы за 500k"},"color":"negative"}],[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"Бросить кубики"},"color":"positive"}]]}';
        $this->vk->messages_send($peer_id, $message, 'photo' . $photos->response[0]->owner_id . '_' . $photos->response[0]->id, $keyboard);
    }

    // Размещение элементов
    public function setting_up_the_field($image, $name, $angle, $dst_x, $dst_y, $dst_width, $dst_height, $src_width, $src_height)
    {
        $image_paste = imagecreatefrompng(__DIR__ . '/resources/' . $name . '.png');
        $image_paste = imagerotate($image_paste, $angle, 0);
        imagecopyresampled($image, $image_paste, $dst_x, $dst_y, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        imagedestroy($image_paste);
    }

    // Размещение фишек
    public function placement_of_chips($image, $color, $dst_x, $dst_y, $size_x, $size_y)
    {
        switch ($color)
        {
            case 1:
                $this->setting_up_the_field($image, 'red_chip', 0, $dst_x, $dst_y, $size_x, $size_y, 36, 37);
                break;
            case 2:
                $this->setting_up_the_field($image, 'blue_chip', 0, $dst_x, $dst_y, $size_x, $size_y, 36, 38);
                break;
            case 3:
                $this->setting_up_the_field($image, 'green_chip', 0, $dst_x, $dst_y, $size_x, $size_y, 36, 38);
                break;
            case 4:
                $this->setting_up_the_field($image, 'purple_chip', 0, $dst_x, $dst_y, $size_x, $size_y, 35, 37);
                break;
        }
    }

    // Сбор баланса игроков
    public function player_balance($peer_id)
    {
        $message = "\nБаланс игроков";
        $players = $this->mysqli->query("SELECT `color`, `money` FROM `monopoly`.`{$peer_id}_players` ORDER BY `color` ASC");
        while ($row = mysqli_fetch_row($players))
            $message = $message . "\n" . $this->print_color($row[0]) . ': ' . number_format($row[1], 0, ',', ',') . 'k';
        return $message . "\n";
    }

    // Селектор цвета
    function print_color($color)
    {
        $colors = array(1 => 'Красный', 'Синий', 'Зелёный', 'Фиолетовый');
        return $colors[$color];
    }

    // Текущий ход
    function current_move($peer_id)
    {
        $current_move = $this->mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 2");
        $current_move = $current_move->fetch_row();
        return array($current_move[0], $this->print_color($current_move[0]));
    }

    // Передача хода
    public function pass_the_move($peer_id, $type_game)
    {
        $current_move = $this->mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 2");
        $current_move = $current_move->fetch_row();
        $duplicate = $this->mysqli->query("SELECT `value` FROM `monopoly`.`{$peer_id}_game_parameters` WHERE `ID` = 9");
        $duplicate = $duplicate->fetch_row();
        if ($duplicate[0])
            $color = $current_move[0];
        else
        {
            $color = $current_move[0];
            $check_color = $this->mysqli->query("SELECT `color` FROM `monopoly`.`{$peer_id}_players` WHERE `color` > $color ORDER BY `color` ASC");
            $check_color = $check_color->fetch_row();
            if (!is_array($check_color))
            {
                $color = $this->mysqli->query("SELECT `color` FROM `monopoly`.`{$peer_id}_players` ORDER BY `color` ASC");
                $color = $color->fetch_row();
                $color = $color[0];
            }
            else
                $color = $check_color[0];
        }
        $this->mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = $color WHERE `{$peer_id}_game_parameters`.`ID` = 2;");
        $this->mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 0 WHERE `ID` = 3;");
        $this->mysqli->query("UPDATE `monopoly`.`{$peer_id}_game_parameters` SET `value` = 1 WHERE `ID` = 4;");
        $this->mysqli->query("UPDATE `monopoly`.`{$peer_id}_players` SET `deal` = '{\"color\":null,\"money_give\":0,\"money_get\":0,\"give\":[],\"get\":[]}'");
        $this->mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `recruiter` = 0 WHERE `level` = -1;");
        $this->mysqli->query("UPDATE `monopoly`.`{$peer_id}_map` SET `level` = `level` + 1 WHERE `level` < 0;");
        return $this->print_color($color);
    }

    // Проверка игровых условий по параметрам
    function checking_game_conditions($conditions)
    {
        foreach ($conditions as $index => $value)
        {
            switch ($index)
            {
                case 'it_cant_be_done_now':
                    if ($value[0])
                        return array('Сейчас это действие выполнить нельзя');
                    break;
                case 'in_the_current_format_of_the_game_this_color_cannot_be_selected':
                    if ($value[0] > $value[1])
                        return array('В текущем формате игры этот цвет выбрать нельзя.');
                    break;
                case 'user_is_already_in_the_game':
                    if (is_array($value[0]))
                        return array('Это не сработает, клоун!');
                    break;
                case 'checking_the_current_progress':
                    if ($value[0] != $value[1])
                        return array('Сейчас не твой ход!');
                    break;
                case 'insufficient_funds':
                    if ($value[0] < $value[1])
                        return array('Недостаточно средств!', '{"inline":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\":\"1\"}","label":"На аукцион"},"color":"negative"}]]}');
                    break;
                case 'field_cannot_be_auctioned':
                    if (is_array($value[0]))
                        return array('Данное поле нельзя выставить на аукцион');
                    break;
                case 'field_is_already_participating_in_the_auction':
                    if ($value[0])
                        return array('Поле уже участвует в аукционе');
                    break;
                case 'you_dont_own_all_fields_of_this_color':
                    if (is_array($value[0]))
                        return array('Вам не принадлежат все поля данного цвета');
                    break;
                case 'field_contains_a_maximum_of_branches':
                    if ($value[0] == 5)
                        return array('Данное поле содержит максимум филиалов');
                    break;
                case 'impossible_to_build_a_branch':
                    if (!$value[0])
                        return array('Сейчас невозможно построить филиал');
                    break;
                case 'presence_of_a_fine':
                    if (!$value[0])
                        return array('У вас не назначено оплат');
                    break;
                case 'you_are_not_under_arrest':
                    if (!$value[0])
                        return array('Вы не находитесь под арестом!');
                    break;
                case 'number_out_of_range':
                    if (0 < $value[0] xor $value[0] < 7)
                        return array('Число вне диапазона!');
                    break;
                case 'you_cant_lace_a_bet':
                    if (!isset($value[0]->jackpot))
                        return array('Вы не можете совершать действия в казино.');
                    break;
                case 'no_numbers_selected':
                    if (!count($value[0]->element))
                        return array('Вы не выбрали ни одного числа');
                    break;
                case 'field_does_not_belong_to_you':
                    if ($value[0] != $value[1])
                        return array('Данное поле вам не принадлежит.');
                    break;
                case 'field_has_already_been_laid':
                    if ($value[0] < 0)
                        return array('Поле уже заложено');
                    break;
                case 'field_is_not_laid':
                    if ($value[0] >= 0)
                        return array('Поле не было заложено');
                    break;
                case 'sell_the_branches_first':
                    if (is_array($value[0]) and !$value[1])
                        return array('Перед продажей поля, необходимо продать все филиалы относящиеся к данной франшизе.');
                    break;
                case 'field_has_already_been_redeemed':
                    if ($value[0] > 0)
                        return array('Поле уже выкуплено');
                    break;
                case 'you_cant_send_the_contract_to_yourself':
                    if ($value[0] == $value[1])
                        return array('Вы не можете отправить контракт себе');
                    break;
                case 'contract_was_drawn_up_incorrectly':
                    foreach ($value[1]['get'] as $name)
                    {
                        $recruiter = $this->mysqli->query("SELECT `recruiter` FROM `monopoly`.`{$value[0]}_map` WHERE `name` = '$name'");
                        $recruiter = $recruiter->fetch_row();
                        if ($value[1]['color'] != $recruiter[0])
                            return array('Контракт был составлен неправильно');
                    }
                    break;
            }
        }
        return false;
    }
}