import { StatusCardAccordion } from '@/components/Cards';
import { useContext, useState, useEffect } from 'react';
import { DataContext } from '@/DataContext';
import { ListGroup, Button, Form, InputGroup } from 'react-bootstrap';

const TaskTrackerCard = ({ getGlobalData, setGlobalData, manifest }) => {
  const context = useContext(DataContext);
  const [tasks, setTasks] = useState([]);
  const [input, setInput] = useState('');

  useEffect(() => {
    const fetchGlobalData = async () => {
      const globalData = await getGlobalData(manifest.id, context);
      setTasks(globalData.tasks || []);
    };

    fetchGlobalData();
  }, [context, getGlobalData, manifest.id]);

  const updateGlobalData = async (newTasks) => {
    const data = { tasks: newTasks };
    await setGlobalData(manifest.id, data);
    setTasks(newTasks);
  }

  const addTask = () => {
    const newTasks = [...tasks, { id: Date.now(), title: input, complete: false }];
    setInput('');
    updateGlobalData(newTasks);
  }

  const removeTask = (id) => {
    updateGlobalData(tasks.filter(task => task.id !== id));
  }

  const toggleTaskStatus = (id) => {
    const newTasks = tasks.map(t => t.id === id ? { ...t, complete: !t.complete } : t);
    updateGlobalData(newTasks);
  }

  return (
    <StatusCardAccordion header={manifest.title}>
      <ListGroup variant="flush">
        {tasks.map(task => (
          <ListGroup.Item key={task.id} className="d-flex justify-content-between align-items-center">
            <Form.Check 
              type="checkbox" 
              checked={task.complete} 
              onChange={() => toggleTaskStatus(task.id)} 
              label={task.title}
            />
            <Button variant="danger" size="sm" onClick={() => removeTask(task.id)}>Remove</Button>
          </ListGroup.Item>
        ))}
      </ListGroup>
      <InputGroup className="mt-3">
        <Form.Control
          placeholder="New task" 
          value={input}
          onChange={(e) => setInput(e.target.value)}
          onKeyDown={(e) => { if (e.key === 'Enter') addTask() }}
        />
        <Button onClick={() => addTask()}>Add</Button>
      </InputGroup>
    </StatusCardAccordion>
  );
};

export default TaskTrackerCard;
