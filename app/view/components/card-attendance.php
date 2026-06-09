<template id="input-Horarios">
    <div class="linha-dia">
        <div class="container-horarios">
            <div class="container-circulo">
                <div class="circule-data">
                    <span></span>
                </div>
            </div>
            <div class="linha-vertical"></div>
            <div class="header-data">
                <span id="header-week-name"><strong></strong></span>
                <span id="header-month"></span>
            </div>
            <div class="items-horarios input-colunm">
                <span>Entrada</span>
                <input class="horario-input" maxlength="5" type="time" name="entrada" value="">
            </div>

            <div class="items-horarios input-colunm">
                <span>Saida</span>
                <input class="horario-input" maxlength="5" type="time" name="saida" value="">
            </div>

            <div class="items-horarios input-colunm">
                <span>Entrada</span>
                <input class="horario-input" maxlength="5" type="time" name="entrada" value="">
            </div>

            <div class="items-horarios input-colunm">
                <span>Saida</span>
                <input class="horario-input" maxlength="5" type="time" name="saida" value="">
            </div>

            <div class="items-botoes">
                <i class="fa-solid fa-pen-to-square botao-calendario" id="btn-editar"
                    onclick="editar_horario(this)"></i>
                <i class="fa-solid fa-check btn-confirmar botao-calendario d-none" id="btn-confirmar"
                    onclick="confirmar_horario(this)"></i>
                <i class="fa-solid fa-xmark btn-cancelar botao-calendario d-none" id="btn-cancelar"
                    onclick="cancelar_horario(this)"></i>
                <i class="fa-solid fa-trash-can botao-calendario" onclick="remover_horario()"></i>
            </div>
        </div>
    </div>
</template>