document.addEventListener('DOMContentLoaded', () => {
  const formulario = document.getElementById('form-medico');
  const urlParams = new URLSearchParams(window.location.search);
  const medicoId = urlParams.get('id');

  async function carregarEspecialidades(selecionada = null) {
    try {
      const response = await fetch('/Software_Seguro/application/index.php?action=listarEspecialidades');
      const resultado = await response.json();

      if (!resultado.success || !resultado.data) {
        throw new Error('Erro ao carregar especialidades');
      }

      const select = document.getElementById('especialidade');
      resultado.data.forEach(especialidade => {
        const option = document.createElement('option');
        option.value = especialidade.id;
        option.textContent = especialidade.nome;
        if (especialidade.id === selecionada) {
          option.selected = true;
        }
        select.appendChild(option);
      });
    } catch (error) {
      console.error('Erro ao carregar especialidades:', error);
    }
  }

  async function carregarMedico(id) {
    try {
      const response = await fetch(`/Software_Seguro/application/index.php?action=buscarMedico&id=${id}`);
      const resultado = await response.json();

      if (!resultado.success || !resultado.data) {
        throw new Error('Erro ao buscar médico');
      }

      const medico = resultado.data;
      document.getElementById('id').value = medico.id;
      document.getElementById('nome').value = medico.nome;
      document.getElementById('crm').value = medico.crm;
      await carregarEspecialidades(medico.especialidadeId);
    } catch (error) {
      console.error('Erro ao carregar dados do médico:', error);
    }
  }

  if (formulario) {
    formulario.addEventListener('submit', async (event) => {
      event.preventDefault();

      const dados = new FormData(formulario);
      const objeto = Object.fromEntries(dados.entries());
      const action = objeto.id ? 'atualizarMedico' : 'cadastrarMedico';

      try {
        const response = await fetch(`/Software_Seguro/application/index.php?action=${action}`, {
          method: 'POST',
          body: new URLSearchParams(objeto),
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        });

        const resultado = await response.json();

        if (resultado.success) {
          alert(`Médico ${objeto.id ? 'atualizado' : 'cadastrado'} com sucesso!`);
          window.location.href = '?pagina=listagem-medicos';
        } else {
          throw new Error(resultado.message || 'Erro ao processar dados do médico.');
        }
      } catch (error) {
        console.error('Erro ao enviar dados:', error);
        alert('Erro ao processar os dados do médico.');
      }
    });

    if (medicoId) {
      carregarMedico(medicoId);
    } else {
      carregarEspecialidades();
    }
  }
});
