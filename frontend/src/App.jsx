import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import Home from "./controllers/Home";
import Login from "./controllers/Login";
import UserList from "./controllers/UserList";
import NotFound from "./controllers/NotFound";
import UserDetail from './controllers/UserDetail';
import CreateUserForm from './controllers/CreateUserForm';

function App() {
    return (
        <Router>
            <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/login" element={<Login />} />
                <Route path="/userlist" element={<UserList />} />
                <Route path="/user/:nick" element={<UserDetail />} />
                <Route path="/create-user" element={<CreateUserForm />} />
                <Route path="*" element={<NotFound />} />
            </Routes>
        </Router>
    );
}

export default App;
