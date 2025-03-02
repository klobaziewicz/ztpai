import { useEffect, useState } from "react";

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
            <div>
                {data ? data.message : "Loading..."}
            </div>
            <div>
                wow!
            </div>
        </div>
    );
}

export default Home;
