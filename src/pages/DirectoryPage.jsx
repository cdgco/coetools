import { useContext, useState } from 'react';
import { DefaultLayout } from '@/layouts';
import { DataContext } from '@/DataContext';
import { DataTable } from 'primereact/datatable';
import { Column } from 'primereact/column';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTimes, faCheck, faExternalLinkAlt, faSearch, faPlus, faFilterCircleXmark, faPencil } from '@fortawesome/free-solid-svg-icons';
import { Badge, Button, FormSelect, OverlayTrigger, Tooltip } from 'react-bootstrap';
import { Row, Col } from 'react-bootstrap';
import { InputText } from 'primereact/inputtext';
import { FilterMatchMode } from 'primereact/api';
import { TriStateCheckbox } from 'primereact/tristatecheckbox';
import { Modals } from '@/components';
import { addTool, updateTool } from '@/utils';
import { directoryPageLoader } from '@/extensions';

function DirectoryPage() {
  const { data, nightMode, setData } = useContext(DataContext);
  const [expandedRows, setExpandedRows] = useState(null);
  const [globalFilterValue, setGlobalFilterValue] = useState('');
  const [editMode, setEditMode] = useState(false);
  const [tool, setTool] = useState(null);
  const [showToolModal, setShowToolModal] = useState(false);
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [modalMode, setModalMode] = useState('add');
  const [filters, setFilters] = useState({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    tool_name: { value: null, matchMode: FilterMatchMode.CONTAINS },
    category: { value: null, matchMode: FilterMatchMode.CONTAINS },
    staff_only: { value: null, matchMode: FilterMatchMode.CONTAINS },
  });
  const [deletedTools, setDeletedTools] = useState([]);
  const [newTools, setNewTools] = useState([]);

  let toolData = (
    data.user.staff 
      ? data.tools.tools 
      : data.tools.tools.filter(tool => tool.staff_only == 0)
  );

  let extensionTools = directoryPageLoader(data.user);
  toolData = [...toolData, ...extensionTools];

  const dedupeTools = (tools) => {
    return tools.filter((tool, index, self) =>
      index === self.findIndex((t) => ( t.id === tool.id ))
    );
  }

  const rowExpansionTemplate = (curToolData) => {
    return (
      <div className="p-3">
        <Row className="mb-4">
          <Col xs={2} className="fw-bold">Description</Col>
          <Col>{curToolData.tool_description}</Col>
        </Row>
        <hr />
        <Row className="mt-4">
          <Col xs={2} className="fw-bold">Link</Col>
          <Col><a href={curToolData.link}>{curToolData.link}</a></Col>
        </Row>
      </div>
    );
  }

  const submitModal = (mode, tool) => {
    if (mode === 'add') {
      addTool(tool);
      setData({ ...data, tools: { ...data.tools, tools: [...data.tools.tools, tool] } });
      setNewTools([...newTools, tool]);

      // If category doesn't exist, add it to the list
      if (!data.tools.categories.includes(tool.category)) {
        setData({ ...data, tools: { ...data.tools, categories: [...data.tools.categories, tool.category] } });
      }

      setShowToolModal(false);
    } else {
      updateTool(tool);

      setData({...data,
        tools: {
          ...data.tools,
          tools: data.tools.tools.map(t => t.id === tool.id ? tool : t)
        }
      })

      // If category doesn't exist, add it to the list
      if (!data.tools.categories.includes(tool.category)) {
        setData({ ...data, tools: { ...data.tools, categories: [...data.tools.categories, tool.category] } });
      }

      setShowToolModal(false);
    }
  }

  const initFilters = () => {
    setFilters({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      tool_name: { value: null, matchMode: FilterMatchMode.CONTAINS },
      category: { value: null, matchMode: FilterMatchMode.CONTAINS },
      staff_only: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });
    setGlobalFilterValue('');
  }

  const onGlobalFilterChange = (e) => {
    const value = e.target.value;
    let _filters = { ...filters };
    _filters['global'].value = value;
    setFilters(_filters);
    setGlobalFilterValue(value);
  };

  const actionTemplate = (curTool) => {
    let editable = true;
    if (extensionTools.find(tool => tool.id === curTool.id)) {
      editable = false;
    }

    return (
      <div className="d-flex gap-2">
      <OverlayTrigger overlay={<Tooltip id="tooltip-disabled">Open Tool</Tooltip>}>
        <a href={curTool.link} target="_blank">
          <Button variant="outline-primary">
            <FontAwesomeIcon icon={faExternalLinkAlt} color={nightMode ? 'white' : 'primary'} />
          </Button>
        </a>
      </OverlayTrigger>
      { editMode && editable &&
        <>
          <OverlayTrigger overlay={<Tooltip id="tooltip-disabled">Edit Tool</Tooltip>}>
            <Button variant="outline-warning" onClick={() => { setTool(curTool); setModalMode('edit'); setShowToolModal(true); }}>
              <FontAwesomeIcon icon={faPencil} color={nightMode ? 'white' : 'warning'} />
            </Button>
          </OverlayTrigger>
          <OverlayTrigger overlay={<Tooltip id="tooltip-disabled">Delete Tool</Tooltip>}>
            <Button variant="outline-danger" onClick={() => { setTool(curTool); setShowDeleteModal(true); }}>
              <FontAwesomeIcon icon={faTimes} color={nightMode ? 'white' : 'danger'} />
            </Button>
          </OverlayTrigger>
        </>
      }
      </div>
    );
  }

  const staffOnlyTemplate = (rowData) => {
    let staff = rowData.staff_only == 1;
    return (
      <Badge bg={staff ? 'success' : 'danger'} className="squareBadge">
        <FontAwesomeIcon icon={staff ? faCheck : faTimes} size='lg' />
      </Badge>
    )
  }

  const staffOnlyFilterElement = (options) => {
      return (
        <div className="d-flex gap-2 align-items-center">
          <TriStateCheckbox value={options.value === 'fa-check' ? true : options.value === 'fa-times' ? false : null} 
          onChange={(e) => options.filterApplyCallback(e.value ? 'fa-check' : e.value === false ? 'fa-times' : null)} /> Staff Only
        </div>
      );
  };

  const categoriesFilterTemplate = (options) => {
    return (
        <FormSelect value={options.value ? options.value : ''}
        onChange={(e) => options.filterCallback(e.target.value)}>
            {data.tools.categories.map(category => (
                <option key={category} value={category}>{category}</option>
            ))}
        </FormSelect>
    );
  };

  const renderHeader = () => {
    return (
      <div className="d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div className="d-flex flex-wrap gap-2">
          { data.user.staff &&
            <>
              <Button variant="primary" onClick={() => { setTool(null); setModalMode('add');  setShowToolModal(true); } }>
                <FontAwesomeIcon icon={faPlus} /> New
              </Button> 
              <Button variant="primary" onClick={() => setEditMode(!editMode)}>
                { editMode ? <><FontAwesomeIcon icon={faTimes} /> Done</> : <><FontAwesomeIcon icon={faPencil} /> Edit</> }
              </Button>
            </>
          }
          <Button variant="secondary" onClick={() => initFilters()} disabled={Object.keys(filters).every(key => filters[key].value === null) && globalFilterValue === ''}>
            <FontAwesomeIcon icon={faFilterCircleXmark} /> Clear
          </Button>
        </div>
        <span className="p-input-icon-left">
            <FontAwesomeIcon icon={faSearch} />
            <InputText value={globalFilterValue} onChange={onGlobalFilterChange} placeholder="Search" />
        </span>
      </div>
    );
  };

  const header = renderHeader();
  return (
    <DefaultLayout title="Tool Directory">
      <Modals.ToolModal tool={tool} setTool={setTool} showModal={showToolModal} setShowModal={setShowToolModal} mode={modalMode} onSubmit={submitModal} />
      <Modals.DeleteToolModal tool={tool} showModal={showDeleteModal} setShowModal={setShowDeleteModal} setDeletedTools={setDeletedTools} deletedTools={deletedTools} />
      <div className={`p-5 mb-4 rounded-3 ${nightMode ? 'bg-dark nightMode' : ''}`}>
        <h2>Tool Directory</h2><br />
          <DataTable value={dedupeTools([...toolData, ...newTools]).filter(tool => !deletedTools.includes(tool.id))}
          paginator rows={10} 
          header={header}
          globalFilterFields={['tool_name', 'category', 'tool_description', 'link']}
          tableStyle={{ minWidth: '50rem' }}
          filters={filters} filterDisplay="menu"
          removableSort
          rowExpansionTemplate={rowExpansionTemplate}
          onFilter={(e) => setFilters(e.filters)}
          sortField='tool_name' sortOrder={1} emptyMessage="No tools found."
          expandedRows={expandedRows} onRowToggle={(e) => setExpandedRows(e.data)}
          dataKey="id"
          >
            <Column expander={true} style={{ width: '5rem' }} />
            <Column field="tool_name" header="Name" sortable filter />
            <Column field="category" header="Category" sortable filter filterElement={categoriesFilterTemplate} showFilterMenuOptions={false} />
            { data.user.staff && <Column field="staff_only" header="Staff Only" sortable body={staffOnlyTemplate} filter 
              showFilterMenuOptions={false} filterElement={staffOnlyFilterElement} showApplyButton={false} showClearButton={false} /> }
            <Column header="Action" exportable={false} body={actionTemplate} />
          </DataTable>
      </div>
      
    </DefaultLayout>      
  )
}

export default DirectoryPage;
