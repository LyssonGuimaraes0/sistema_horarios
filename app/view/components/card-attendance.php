<!--Chama Helper Permission--->

<?php

use App\helpers\PermissionHelper;

?>

<template id="input-Horarios">
    <div class="linha-dia">
        <div class="container-horarios">
            <div class="container-circulo">
                <div class="circule-data">
                    <span id="date-circule"></span>
                </div>
            </div>
            <div class="linha-vertical"></div>
            <div class="header-data">
                <span id="header-week-name"></span>
                <span id="header-month"></span>
            </div>
            <div class="items-horarios input-colunm">
                <span>Entrada</span>
                <input class="horario-input" step="60" type="time" name="entrada">
            </div>

            <!--------------------------------->

            <!--Verifica se os usuarios é Servidor Publico e adiciona novos horarios-->

            <?php if (PermissionHelper::isServidorPublico($user)): ?>

                <div class="items-horarios input-colunm">
                    <span>Saida Almoco</span>
                    <input class="horario-input" step="60" type="time" name="saida_almoco">
                </div>

                <div class="items-horarios input-colunm">
                    <span>Entrada Almoco</span>
                    <input class="horario-input" step="60" type="time" name="volta_almoco">
                </div>

            <?php endif ?>

            <div class="items-horarios input-colunm">
                <span>Saida</span>
                <input class="horario-input" step="60" type="time" name="saida">
            </div>

            <div class="items-botoes">
                <button type='submit' class='btn-calendario' name='dia' data-action="submit">Confirmar</button>
                <i class='fa-solid fa-file-alt botao-calendario' data-action="certificate"></i>
            </div>
        </div>
    </div>
</template>