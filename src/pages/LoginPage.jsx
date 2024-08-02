import { Form, Row, Col, Button, Alert } from 'react-bootstrap';
import { useContext, useState } from 'react';
import { DefaultLayout } from '@/layouts';
import { DataContext } from '@/DataContext';
import { useNavigate } from 'react-router-dom';
import { useEffect } from 'react';
import { toast } from 'react-toastify';
import LoadingOverlay from '@/components/LoadingOverlay';

function LoginPage() {
    const { nightMode, isAuthenticated, setIsAuthenticated, fetchDataOnLogin } = useContext(DataContext);
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [message, setMessage] = useState('');
    const [variant, setVariant] = useState('danger');
    const [externalAuth, setExternalAuth] = useState(true);
    const navigate = useNavigate();

    useEffect(() => {
        if (isAuthenticated) {
            navigate('/');
        } else {
            const fetchAuthType = async () => {
                const authType = await fetch(import.meta.env.VITE_API_URL + '/auth/info.php');
                const authTypeJson = await authType.json();
                if (authTypeJson.success) {
                    if (authTypeJson.type == 'external') {
                        window.location.href = import.meta.env.VITE_API_URL + '/auth/login.php?redirect=' + encodeURIComponent(window.location.href);
                    } else {
                        setExternalAuth(false);
                    }
                }
            }

            fetchAuthType();
        }
    }, [isAuthenticated, navigate]);

    const handleSubmit = async (e) => {
        e.preventDefault();

        const response = await fetch(import.meta.env.VITE_API_URL + '/auth/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username, password }),
        });

        const result = await response.json();

        if (result.success) {
            setVariant('success');
            await fetchDataOnLogin();
            toast.success("Logged in!", { autoClose: 800 });
            setIsAuthenticated(true);
        } else {
            setMessage(result.message);
            setVariant('danger');
            setIsAuthenticated(false);
        }
    };

    return (
        !externalAuth ? (
            <DefaultLayout>
                <div className={`p-5 rounded-3 ${nightMode ? 'bg-dark' : ''}`}>
                    <div className="text-center">
                        <h1>Sign in to { import.meta.env.MODE === 'production' ? import.meta.env.VITE_APP_NAME : `${import.meta.env.VITE_APP_NAME} DEV` }</h1>
                    </div>
                    <br />
                    <div className="row justify-content-md-center">
                        <div className="col-lg-6">
                            {message && <Alert variant={variant}>{message}</Alert>}
                            <Form onSubmit={handleSubmit}>
                                <Row className="mb-3">
                                    <Col>
                                        <label htmlFor="username" className="form-label text-start">Username</label>
                                        <Form.Control 
                                            value={username}
                                            onChange={(e) => setUsername(e.target.value)}
                                            required
                                        />
                                    </Col>
                                </Row>
                                <Row className="mb-4">
                                    <Col>
                                        <label htmlFor="password" className="form-label text-start">Password</label>
                                        <Form.Control 
                                            type="password"
                                            value={password}
                                            onChange={(e) => setPassword(e.target.value)}
                                            required
                                        />
                                    </Col>
                                </Row>
                                <Row>
                                    <Col>
                                        <Button variant="primary" type="submit" className="w-100">
                                            Sign in
                                        </Button>
                                    </Col>
                                </Row>
                            </Form>
                        </div>
                    </div>
                </div>
            </DefaultLayout>
        ) : <LoadingOverlay visible={true} />
    );
}

export default LoginPage;
