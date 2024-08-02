import { useContext } from 'react';
import { DataContext } from '@/DataContext';

const StatusCardStatic = ({ children }) => {
  const { nightMode } = useContext(DataContext);

    return (
      <div className="col-12 p-0 pb-2 px-1">
        <div className={`card text-bg-dark accordion border-0`} data-bs-theme={nightMode ? 'dark' : 'light'}>
          <div className="card-header p-3 accordion-item no-after">
            {children}
          </div>
        </div>
      </div>
    );
};

export default StatusCardStatic;
