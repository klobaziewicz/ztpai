import { BrowserRouter as Router, Route, Switch } from "react-router-dom";
import Home from "./controllers/Home";
import Login from "./controllers/Login";
import UserList from "./controllers/UserList";
import NotFound from "./controllers/NotFound";

function App() {
    return (
        <Router>
            <Switch>
                <Route exact path="/" component={Home} />
                <Route path="/login" component={Login} />
                <Route path="/userlist" component={UserList} />
                <Route component={NotFound} />
            </Switch>
        </Router>
    );
}

export default App;