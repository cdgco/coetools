import { useContext } from 'react';
import { DataContext } from '@/DataContext';
import { Accordion } from 'react-bootstrap';

const StatusCardAccordion = ({ header, children }) => {
  const { nightMode } = useContext(DataContext);

    return (
      <div className="col-12 p-0 pb-2 px-1">
        <Accordion data-bs-theme={nightMode ? 'dark' : 'light'}>
          <Accordion.Item eventKey="0">
            <Accordion.Header>
              {header}
            </Accordion.Header>
            <Accordion.Body>
              {children}
            </Accordion.Body>
          </Accordion.Item>
        </Accordion>
      </div>
    );
};

export default StatusCardAccordion;
