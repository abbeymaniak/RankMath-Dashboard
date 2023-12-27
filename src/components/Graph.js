import React, { useState, useEffect } from "react";
import { LineChart, Line, CartesianGrid, XAxis, YAxis } from "recharts";

const Graph = () => {
  const [selectedOption, setSelectedOption] = useState("7days");
  const [data, setData] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await window.WebGLProgram.apiFetch({
          path: `http://test.local/wp-json/react/v1/data/${selectedOption}`,
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        });

        // const response = await fetch(
        //   `http://test.local/wp-json/react/v1/data/${selectedOption}`,
        //   {
        //     method: "GET",
        //     headers: {
        //       "Content-Type": "application/json",
        //     },
        //   }
        // );

        const json_data = await response.json();
        console.log(json_data);
        setData(json_data);
      } catch (error) {
        console.log("Error fetching data: ".error);
      }
    };
    fetchData();
  }, [selectedOption]);

  const handleSelectChange = (e) => {
    setSelectedOption(e.target.value);
  };

  return (
    <div>
      {data && (
        <>
          <div className="d-flex justify-content-between">
            <h2 className="pb-2">{_("Graph Widget", "rankmath-test")}</h2>
            <select value={selectedOption} onChange={handleSelectChange}>
              <option value="7days">
                {__("Last 7 days", "rankmath-test")}
              </option>
              <option value="15days">
                {__("Last 15days", "rankmath-test")}
              </option>
              <option value="1month">
                {__("Last 1month", "rankmath-test")}
              </option>
            </select>
          </div>
          <LineChart width={500} height={400} data={data}>
            <Line type="monotone" dataKey="uv" stroke="#8884d8" />
            <CartesianGrid stroke="#ccc" />
            <XAxis dataKey="name" />
            <YAxis />
          </LineChart>
        </>
      )}
    </div>
  );
};

export default Graph;
