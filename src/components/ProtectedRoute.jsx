import { useContext } from "react";
import { Navigate } from "react-router-dom";
import { DataContext } from "@/DataContext";

const ProtectedRoute = ({ children }) => {
    const { isAuthenticated } = useContext(DataContext);

    if (!isAuthenticated) {
        return <Navigate to="/login" />;
    }

    return children;
};

export default ProtectedRoute;
