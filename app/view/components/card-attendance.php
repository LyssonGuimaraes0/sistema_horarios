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
                <input class="horario-input" maxlength="5" type="time" name="entrada" value="">
            </div>
            <div class="items-horarios input-colunm">
                <span>Saida Almoco</span>
                <input class="horario-input" maxlength="5" type="time" name="saida_almoco" value="">
            </div>

            <div class="items-horarios input-colunm">
                <span>Entrada Almoco</span>
                <input class="horario-input" maxlength="5" type="time" name="volta_almoco" value="">
            </div>

            <div class="items-horarios input-colunm">
                <span>Saida</span>
                <input class="horario-input" maxlength="5" type="time" name="saida" value="">
            </div>

            <div class="items-botoes">
                <button type='submit' class='btn-calendario' name='dia' value='$dia'>Confirmar</button>
                <i class='fa-solid fa-file-alt botao-calendario'></i>
            </div>
        </div>
    </div>
</template>