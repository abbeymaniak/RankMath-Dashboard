import React, { useState, useEffect } from "react";
import { SelectControl } from "@wordpress/components";
import apiFetch from "@wordpress/api-fetch";
import { LineChart, Line, CartesianGrid, XAxis, YAxis } from "recharts";
import { __ } from "@wordpress/i18n";

const Graph = () => {
  const [selectedOption, setSelectedOption] = useState("7days");
  const [data, setData] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await apiFetch({
          path: `react/v1/data/${selectedOption}`,
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        });

        const json_data = await response;

        setData(json_data);
      } catch (error) {
        console.error(__("Error fetching data:", "rankmath-test"), error);
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
            <h2 className="pb-2">{__("Graph Widget", "rankmath-test")}</h2>
            <SelectControl
              label={__("duration:", "rankmath-test")}
              value={selectedOption}
              options={[
                {
                  label: __("Last 7 days", "rankmath-test"),
                  value: "7days",
                },
                {
                  label: __("Last 15 days", "rankmath-test"),
                  value: "15days",
                },
                {
                  label: __("Last 1 month", "rankmath-test"),
                  value: "1month",
                },
              ]}
              onChange={(value) => setSelectedOption(value)}
            />
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
