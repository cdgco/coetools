import { DefaultLayout } from '@/layouts';
import { useRouteError, useLocation, useSearchParams } from 'react-router-dom';

function ErrorPage({ code }) {
    const { pathname } = useLocation();
    const error = useRouteError();

    code = code || 500
    let message = "Internal Server Error";
    let description = "An error occurred on the server.";

    if (error && error.error) {
        code = error.status
        message = error.statusText
        description = error.error.message
    } else if (String(error).includes("not found")) {
        code = 404
        message = "Not Found"
        description = String(error)
    } else if (code === 404) {
        message = "Not Found"
        description = "No route matches URL \"" + pathname + "\""
    } else if (error) {
        message = "Internal Server Error"
        description = String(error)
    } 


    // Check URL query string for custom error message from API response
    const [searchParams] = useSearchParams();
    const customMessage = searchParams.get("message");
    const customDescription = searchParams.get("description");
    const customCode = searchParams.get("code");
    
    if (customMessage) {
        message = customMessage;
    }

    if (customDescription) {
        description = customDescription;
    }

    if (customCode) {
        code = customCode;
    }

    return (
        <DefaultLayout title={message}>
            <div className={`p-5 mb-4 rounded-3 d-flex flex-column align-items-center`}>
                <h1>{code}</h1>
                <h3>{message}</h3>
                <p>{description}</p>    
            </div>
        </DefaultLayout>
    )
}

export default ErrorPage;
