import { useEffect, useState } from "react";
import Nav from './Nav'
import '../style/posts.css';

function Home() {
    const [data, setData] = useState(null);

    useEffect(() => {
        const token=localStorage.getItem('token');
        fetch("http://localhost:8000/api/home",{
            headers: {
                'Content-type': 'application/json',
                'Authorization': `Bearer ${token}`,
            }
        })
            .then((response) => response.json())
            .then((data) => setData(data))
            .catch((error) => console.error("Error:", error));
    }, []);

    return (
        <div>
            <Nav />
            <div>
                {data ? data.dane : "Loading..."}
                <div className="text">
                    <h1>Witaj! kliknij w przyciski nawigacji</h1>
                </div>
            </div>
        </div>
    );
}

export default Home;
