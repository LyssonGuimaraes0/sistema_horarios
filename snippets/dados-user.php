<section class="dados-user">
<div class="card-user">
  <div class="coluna">
    <div class="item">
      <p class="label">Nome</p>
      <p class="valor"><?php echo $dados_user['nome']?></p>
    </div>
    <div class="item">
      <p class="label">Cargo</p>
      <p class="valor"><?php echo $dados_user['cargo']?></p>
    </div>
  </div>

  <div class="coluna">
    <div class="item">
      <p class="label">Setor</p>
      <p class="valor"><?php echo $dados_user['setor']?></p>
    </div>
  </div>
</div>




</section>

<style>
  .card-user {
  display: flex;
  justify-content: space-between;
  background: white;
  padding: 20px 25px;
  border-radius: 12px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  width: 100%;
  height: 150px;
  margin: 20px auto;
  font-family: Arial, sans-serif;
}

.coluna {
  display: flex;
  flex-direction: column;
  gap: 20px;
  min-width: 45%; /* para manter largura próxima */
}

.item .label {
  font-size: 13px;
  color: #758291;
  margin: 0 0 5px 0;
}

.item .valor {
  font-weight: 700;
  font-size: 16px;
  margin: 0;
  color: #222;
}

/* Responsivo: em telas pequenas, empilha as colunas */
@media (max-width: 480px) {
  .card-user {
    flex-direction: column;
    padding: 15px;
  }
  
  .coluna {
    min-width: 100%;
  }
}



</style>