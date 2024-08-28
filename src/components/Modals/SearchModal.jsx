import { useState, useEffect, useContext, useRef } from 'react';
import { Modal, Form, ListGroup } from 'react-bootstrap';
import { DataContext } from '@/DataContext';
import { extensionPages } from '@/extensions/pages/manifest';
import Fuse from 'fuse.js'

function SearchModal({ show, setShowModal }) {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState([]);
  const [highlightedIndex, setHighlightedIndex] = useState(0);
  const inputRef = useRef(null);
  const { data, nightMode } = useContext(DataContext);
  const itemRefs = useRef([]);
  const containerRef = useRef(null);
  const [keyboardNavigationActive, setKeyboardNavigationActive] = useState(false);

  let mac = navigator.userAgent.indexOf('Mac') !== -1;

  useEffect(() => {
    if (show) {
      inputRef.current.focus();
      setHighlightedIndex(0); // Reset or set to top option when modal opens or results change
    }
  }, [show, results]);

  useEffect(() => {
    if (query.length > 0 && data.tools && data.tools.tools) {
      let extensionResults = extensionPages.filter(page => (!page.staffOnly || data.user.staff))
        .map(page => ({
          tool_name: page.title,
          tool_description: page.description,
          link: import.meta.env.VITE_FRONTEND_URL + '/#' + page.link,
          tab: '0'
        }));

      let options = [
        ...data.tools.tools,
        {
          tool_name: 'Home',
          tool_description: 'Home',
          link: './',
          tab: '0'
        },
        {
          tool_name: 'Tool Directory',
          tool_description: 'List of all tools',
          link: './#directory',
          tab: '0'
        },
        {
          tool_name: 'Settings',
          tool_description: 'Account Settings',
          link: './#settings',
          tab: '0'
        },
        ...extensionResults
      ]

      const fuseOptions = {
        isCaseSensitive: false,
        includeScore: true,
        shouldSort: true,
        findAllMatches: true,
        useExtendedSearch: true, // This helps tokenization of search query but may introduce weird behavior
        keys: ['tool_name', 'tool_description'],
        threshold: 0.3,
      };
      const fuse = new Fuse(options, fuseOptions);
      const fuseResults = fuse.search(query);
      const toolResults = fuseResults.map(result => result.item).filter((value, index, self) => self.findIndex(t => t.tool_name === value.tool_name) === index);

      setResults(toolResults);
    } else {
      setResults([]);
    }
  }, [query, data]);

  useEffect(() => {
    setQuery('');
  }, [show]);

  const handleKeyDown = (e) => {
    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
      e.preventDefault();
      setKeyboardNavigationActive(true);
      clearTimeout(window.keyboardNavTimeout);

      const newIndex = e.key === 'ArrowDown' 
      ? Math.min(highlightedIndex + 1, results.length - 1) 
      : Math.max(highlightedIndex - 1, 0);

      setHighlightedIndex(newIndex);

      // Scroll to highlighted item
      if (containerRef.current && itemRefs.current[highlightedIndex]) {
        const itemRef = itemRefs.current[highlightedIndex];
        const offsetHeight = highlightedIndex > 0 ? itemRefs.current[highlightedIndex - 1].offsetHeight : itemRef.offsetHeight;

        containerRef.current.scrollTop = e.key === 'ArrowDown' ? 
          itemRef.offsetTop - containerRef.current.offsetTop + itemRef.clientHeight : 
          itemRef.offsetTop - containerRef.current.offsetTop - offsetHeight;
      }
    } else if (e.key === 'Enter' && results[highlightedIndex]) {
      e.preventDefault(); // Prevent form submission or other default actions
      const selectedTool = results[highlightedIndex];
      const target = selectedTool.tab === '1' ? '_blank' : '_self';
      const href = selectedTool.tab === '2' && !data.user.direct_links ? `#frame?frameID=${selectedTool.id}` : selectedTool.link;
      setShowModal(false);
      window.open(href, target);
    }

    window.keyboardNavTimeout = setTimeout(() => {
      setKeyboardNavigationActive(false);
    }, 1000);
  };

  return (
    <Modal show={show} onHide={() => setShowModal(false)} size="lg" onKeyDown={handleKeyDown} data-bs-theme={nightMode ? 'dark' : undefined}>
      <Modal.Body>
        <Form.Control 
          type="search" 
          placeholder="Search tools by name or description"
          value={query}
          onChange={e => setQuery(e.target.value)}
          autoFocus
          size="lg"
          ref={inputRef}
          className="mb-2"
        />
        {query.length > 0 && (
          <ListGroup variant="flush" className="mt-3 scrollable-list-group" ref={containerRef}>
          {results.map((result, index) => (
            <ListGroup.Item 
              key={index}
              ref={el => itemRefs.current[index] = el}
              action
              active={index === highlightedIndex}
              onMouseEnter={() => {
                if (!keyboardNavigationActive) {
                  setHighlightedIndex(index);
                }
              }}
              as="a"
              href={result.tab === '2' && !data.user.direct_links ? `#frame?frameID=${result.id}` : result.link} 
              target={result.tab === '1' ? '_blank' : ''}
              onClick={() => setShowModal(false)}
            >
              <strong>{result.tool_name}</strong> - {result.tool_description}
            </ListGroup.Item>
          ))}
          {results.length === 0 && query.length > 0 && (
            <ListGroup.Item>No results found.</ListGroup.Item>
          )}
        </ListGroup>
        )}
        {query.length === 0 && 
        <small className="text-muted p-1 d-flex flex-wrap align-items-center justify-content-center">Use <span className="badge bg-secondary ms-1 me-1">{mac ? '⌘' : 'Ctrl'} + Shift + F</span>to open. 
        <span className="badge bg-secondary ms-1 me-1">&#x25B2;</span> / <span className="badge bg-secondary ms-1 me-1">&#x25BC;</span> and <span className="badge bg-secondary ms-1 me-1">Enter</span> to navigate, <span className="badge bg-secondary ms-1 me-1">Esc</span> to close.</small>
        }
      </Modal.Body>
    </Modal>
  );
}

export default SearchModal;
