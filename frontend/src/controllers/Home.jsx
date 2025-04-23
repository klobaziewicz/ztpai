import { useEffect, useState } from "react";
import Nav from './Nav'

function Home() {
    const [data, setData] = useState(null);

    useEffect(() => {
        fetch("http://localhost:8000/api/home")
            .then((response) => response.json())
            .then((data) => setData(data))
            .catch((error) => console.error("Error:", error));
    }, []);

    return (
        <div>
            <Nav />
            <div>
                {data ? data.message : "Loading..."}
            </div>
        </div>
    );
}

export default Home;
