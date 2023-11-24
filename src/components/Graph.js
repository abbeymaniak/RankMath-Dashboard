import React, { useState, useEffect } from "react";
import { LineChart, Line, CartesianGrid, XAxis, YAxis } from "recharts";

const Graph = () => {
  const [selectedOption, setSelectedOption] = useState("7days");
  const [data, setData] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch(
          `http://test.local/wp-json/react/v1/data/${selectedOption}`,
          {
            method: "GET",
            headers: {
              "Content-Type": "application/json",
            },
          }
        );

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
            <h2 className="pb-2">Graph Widget</h2>
            <select value={selectedOption} onChange={handleSelectChange}>
              <option value="7days">Last 7days</option>
              <option value="15days">Last 15days</option>
              <option value="1month">Last 1month</option>
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
