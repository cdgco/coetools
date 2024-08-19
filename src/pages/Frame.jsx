import { useContext, useState, useEffect } from 'react';
import { DataContext } from '@/DataContext';
import { FrameLayout } from '@/layouts';
import { useSearchParams } from 'react-router-dom';
import { LoadingOverlay } from '@/components';

function Frame() {
  const { data } = useContext(DataContext);
  const { tools } = data.tools;
  const [pageLoading, setPageLoading] = useState(true);

  // Get the frameID from the URL
  const [urlParams] = useSearchParams();
  const frameID = urlParams.get('frameID');
  const tool = tools.find((t) => t.id === frameID);

  useEffect(() => {
    setPageLoading(true);
  }, [frameID]);

  if (!tool) {
    throw new Error(`Tool with ID ${frameID} not found.`);
  }

  let url = tool.link;

  // Forward the "search", "hostname", and "username" query parameters to the tool
  let params = {};

  if (urlParams.has('search')) {
    params.search = urlParams.get('search');
  }

  if (urlParams.has('hostname')) {
    params.hostname = urlParams.get('hostname');
  }

  if (urlParams.has('username')) {
    params.username = urlParams.get('username');
  }

  var esc = encodeURIComponent;
  var query = Object.keys(params)
      .map(k => esc(k) + '=' + esc(params[k]))
      .join('&');

  if (query) {
    url = url + '?' + query;
  }

  return (
    <FrameLayout url={url} title={tool.tool_name}>
      <LoadingOverlay visible={pageLoading} />
      <iframe src={url} style={{width: '100%', height: 'calc(100vh - 116px)', border: 'none'}}
              onLoad={() => setPageLoading(false)} />
    </FrameLayout>
  );
}

export default Frame;
