const apiBase = 'php';

async function fetchJson(endpoint) {
  const resp = await fetch(`${apiBase}/${endpoint}.php`);
  if (!resp.ok) throw new Error(`Erro ao acessar ${endpoint}`);
  return resp.json();
}

async function postJson(endpoint, payload) {
  const resp = await fetch(`${apiBase}/${endpoint}.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
  });
  if (!resp.ok) {
    const data = await resp.json().catch(() => ({}));
    throw new Error(data.erro || 'Erro desconhecido');
  }
  return resp.json();
}

async function deleteItem(endpoint, id) {
  const resp = await fetch(`${apiBase}/${endpoint}.php?id=${id}`, { method: 'DELETE' });
  if (!resp.ok) throw new Error('Erro ao excluir');
}

function limparSelect(selectId) {
  const select = document.getElementById(selectId);
  if (select) select.innerHTML = '<option value="">Selecione...</option>';
}

async function carregarSelect(endpoint, selectId, labelProp = 'nome') {
  try {
    const dados = await fetchJson(endpoint);
    limparSelect(selectId);
    const select = document.getElementById(selectId);
    dados.forEach(item => {
      const opt = document.createElement('option');
      opt.value = item.id;
      opt.textContent = item[labelProp];
      select.appendChild(opt);
    });
  } catch (err) {
    console.error(err);
  }
}

async function carregarTabela(endpoint, tabelaId, colunas, excluirCallbackName) {
  try {
    const dados = await fetchJson(endpoint);
    const tbody = document.querySelector(`#${tabelaId} tbody`);
    if (!tbody) return;

    tbody.innerHTML = '';
    dados.forEach(item => {
      const tr = document.createElement('tr');
      tr.innerHTML = colunas.map(col => `<td>${item[col]}</td>`).join('') +
        `<td><button class="btn-excluir" onclick="${excluirCallbackName}(${item.id})">Excluir</button></td>`;
      tbody.appendChild(tr);
    });
  } catch (err) {
    console.error(`Erro ao carregar ${endpoint}:`, err);
  }
}

async function excluirGenerico(endpoint, id, recarregarFn, mensagemSucesso, mensagemErro) {
  if (!confirm('Deseja realmente excluir?')) return;
  try {
    await deleteItem(endpoint, id);
    alert(mensagemSucesso);
    recarregarFn();
  } catch (err) {
    alert(mensagemErro);
    console.error(err);
  }
}

async function excluirMedicos(id) {
  await excluirGenerico('medicos', id, carregarMedicos, 'Médico excluído com sucesso.', 'Erro ao excluir médico.');
}

async function carregarMedicos() {
  await carregarTabela('medicos', 'tabela-medicos', ['nome', 'crm', 'especialidade'], 'excluirMedicos');
}

async function carregarPacientes() {
  await carregarTabela('pacientes', 'tabela-pacientes', ['nome', 'email', 'telefone', 'dataNascimento'], 'excluirPaciente');
}

async function carregarEspecialidades() {
  await carregarTabela('especialidades', 'tabela-especialidades', ['nome'], 'excluirEspecialidade');
}

async function enviarFormulario(endpoint, payload, redirecionarPara) {
  try {
    await postJson(endpoint, payload);
    alert('Cadastro realizado com sucesso!');
    window.location.href = redirecionarPara;
  } catch (err) {
    alert('Erro: ' + err.message);
  }
}

function initEventos() {
  const pagina = window.location.pathname;

  if (pagina.includes('cadastro-consulta.html')) {
    carregarSelect('pacientes', 'paciente');
    carregarSelect('medicos', 'medico');
    carregarSelect('especialidades', 'especialidade');
    document.getElementById('form-consulta').addEventListener('submit', e => {
      e.preventDefault();
      const data = document.getElementById('data').value;
      const hora = document.getElementById('hora').value;
      const payload = {
        pacienteId: document.getElementById('paciente').value,
        medicoId: document.getElementById('medico').value,
        especialidadeId: document.getElementById('especialidade').value,
        dataHora: `${data}T${hora}:00`
      };
      enviarFormulario('consultas', payload, 'agenda.html');
    });
  }
}

window.addEventListener('DOMContentLoaded', () => {
  initEventos();
});
