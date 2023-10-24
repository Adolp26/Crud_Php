import React, { useState } from "react";
import './styles.css'
import './style-priority.css'
import { AiTwotoneDelete, AiTwotoneExclamationCircle } from "react-icons/ai";
import api from '../../services/api'


function Notes({ data, handleDelete, handleChangeStatus }) {

    const [changedNote, setChangedNote] = useState('');

    function handleEdit(e, status) {
        e.style.cursor = 'text';
        e.style.borderRadius = '5px';

        if (status) {
            e.style.boxShadow = '0 0 5px white'
        } else {
            e.style.boxShadow = '0 0 5px gray'
        }
    }

    async function handleSave(e, description) {
        e.style.cursor = "default"
        e.style.boxShadow = 'none'

        if (changedNote && changedNote !== description) {
            await api.put(`/tasks/${data.id}`, {
                notes: changedNote,
            })
        }
    }


    return (
        <>
            <li className={data.status ? "notepad-infos-priority" : "notepad-infos"}>
                <div>
                    <strong>{data.title}</strong>
                    <div>
                        <AiTwotoneDelete
                            size="20"
                            onClick={() => handleDelete(data.id)} />
                    </div>
                </div>
                <textarea
                    defaultValue={data.description}

                    onChange={e => setChangedNote(e.target.value)}
                    onBlur={e => handleSave(e.target, data.description)}
                    onClick={e => handleEdit(e.target, data.status)}

                />
                <span>
                    <AiTwotoneExclamationCircle
                        size="20"
                        onClick={() => {
                            if (data.id) {
                                handleChangeStatus(data.id);
                            }
                        }}
                    />
                </span>
            </li>
        </>
    )
}

export default Notes;