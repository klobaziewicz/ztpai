import { useEffect, useState } from "react";
import Nav from "./Nav";

function Posts() {
    const [data, setData] = useState(null);

    useEffect(() => {
    }, []);

    return (
        <div>
            <Nav />
            <div>
                {data ? data.dane : "Loading..."}
            </div>
        </div>
    );
}

export default Posts;