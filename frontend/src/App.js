import React, { useEffect, useState } from "react";
import './Styles/global.css'
import './Styles/sidebar.css'
import './Styles/app.css'
import './Styles/main.css'
import Notes from './Components/Notes'
import './Styles/Form.css'
import api from './services/api'
import RadioButton from "./Components/RadioButton";


function App() {


  const [title, setTitle] = useState('')
  const [description, setDescription] = useState('')
  const [allDescription, setAllDescription] = useState([]);
  const [selectedValue, setSelectedValue] = useState('all')


  /*Criação de anotações */
  async function handleSubmit(e) {
    e.preventDefault();

    const response = await api.post('/tasks', {
      title,
      description
    })

    setTitle('');
    setDescription('');

    if (selectedValue !== 'all') {
      getAllDescription();
    } else {
      setAllDescription([...allDescription, response.data]);
    }
    setSelectedValue('all')
  }

  /*Alteração da cor do botão Salvar conforme preenchimento dos campos */
  useEffect(() => {
    function enableSubmitButton() {
      let btn = document.getElementById('btn_submit')
      btn.style.background = '#ffd3ca'
      if (title && description) {
        btn.style.background = "#eb8f7a"
      }
    }
    enableSubmitButton()
  }, [title, description])




  useEffect(() => {

    getAllDescription();

  }, [])

  /* Listar todos as anotações*/
  async function getAllDescription() {
    const response = await api.get('/tasks');
    setAllDescription(response.data)

  }

  /* Mostrar anotações por radiobuttons*/
  async function loadDescription(statusOption) {
    try {
      const response = await api.get(`/status/${statusOption}`);

      if (response) {
        setAllDescription(response.data);
      }
    } catch (error) {
      console.error("Erro ao carregar descrições:", error);
    }
  }

  /* Verificando valor do Status */
  async function handleChange(e) {
    const statusOption = e.value;
    setSelectedValue(statusOption);

    if (e.checked && statusOption !== 'all') {
      loadDescription(statusOption);
    } else {
      getAllDescription();
    }
  }




  /*Deletando Cards*/
  async function handleDelete(id) {
    const deleteDescription = await api.delete(`/tasks/${id}`);

    if (deleteDescription) {
      setAllDescription(allDescription.filter(note => note.id !== id));
    }
  }


  async function handleChangeStatus(id) {
    const note = await api.put(`/status/${id}`)


    if (note && selectedValue !== 'all') {
      loadDescription(selectedValue);
    } else if (note) {
      getAllDescription();
    }
  }


  return (
    <div id="app">
      <aside>
        <strong>Caderno de Notas</strong>
        <form onSubmit={handleSubmit}>

          <div className="input-block">
            <label htmlFor="title">Titulo da Anotação</label>
            <input required maxLength="30" value={title} onChange={e => setTitle(e.target.value)} />
          </div>
          <div className="input-block">
            <label htmlFor="nota">Anotações</label>
            <textarea
              required
              value={description}
              onChange={e => setDescription(e.target.value)} />
          </div>
          <button id="btn_submit" type="submit">Salvar</button>
        </form>
        <RadioButton
          selectedValue={selectedValue}
          handleChange={handleChange}
        />
      </aside>

      <main>
        <ul>
          {allDescription.map(data => (
            <Notes
              key={data.id}
              data={data}
              handleDelete={handleDelete}
              handleChangeStatus={handleChangeStatus}
            />
          ))}

        </ul>
      </main>
    </div>
  );
}

export default App;
