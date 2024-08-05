import { useState, useEffect } from "react";
import Editor from "@monaco-editor/react";
import Navbar from "./Components/Navbar";
import Axios from "axios";
import "./App.css";
import { Spinner } from "react-bootstrap";
const apiKey = "d3e4ee7a9emsha45d05810b4c0f8p1cbe4ejsncb947d65b3e2";
const appURL = "https://judge0-ce.p.rapidapi.com/submissions";
const demoCode = {
  php: "<?php echo 'Hello, World!'; ?>",
  javascript: "console.log('Hello, World!');",
  python: "print('Hello, World!')",
  java: "public class Main { public static void main(String[] args) { System.out.println('Hello, World!'); } }",
  c: `
  #include <stdio.h>

int main() {
    printf("Hello, World!");
    return 0;
}
  `,
  cpp: "#include <iostream>\nint main() { std::cout << 'Hello, World!'; return 0; }",
};

function Code() {
  const [userCode, setUserCode] = useState(demoCode.php);
  const [userLanguage, setUserLanguage] = useState("php");
  const [userTheme, setUserTheme] = useState("vs-dark");
  const [fontSize, setFontSize] = useState(16);
  const [userInput, setUserInput] = useState("");
  const [userOutput, setUserOutput] = useState("");
  const [loading, setLoading] = useState(false);
  const options = { fontSize: fontSize };

  useEffect(() => {
    setUserCode(demoCode[userLanguage]);
  }, [userLanguage]);

  function Compile() {
    setLoading(true);
    let languageId;
    switch (userLanguage) {
      case "java":
        languageId = 62;
        break;
      case "c":
        languageId = 50;
        break;
      case "cpp":
        languageId = 54;
        break;
      case "python":
        languageId = 71;
        break;
      case "javascript":
        languageId = 63;
        break;
      case "php":
        languageId = 68;
        break;
      default:
        languageId = 68;
    }

    const formData = {
      language_id: languageId,
      source_code: btoa(userCode),
      stdin: btoa(userInput),
    };
    const options = {
      method: "POST",
      url: appURL,
      headers: {
        "content-type": "application/json",
        "X-RapidAPI-Key": apiKey,
        "X-RapidAPI-Host": "judge0-ce.p.rapidapi.com",
      },
      data: {
        base64_encoded: "true",
        fields: "*",
        ...formData,
      },
    };

    Axios.request(options)
      .then(function (response) {
        const token = response.data.token;
        checkStatus(token);
      })
      .catch((err) => {
        let error = err.response ? err.response.data : err;
        setLoading(false);
        console.log(error);
      });
  }

  const checkStatus = async (token) => {
    const options = {
      method: "GET",
      url: `${appURL}/${token}`,
      params: { base64_encoded: "true", fields: "*" },
      headers: {
        "X-RapidAPI-Key": apiKey,
        "X-RapidAPI-Host": "judge0-ce.p.rapidapi.com",
      },
    };
    try {
      let response = await Axios.request(options);
      let statusId = response.data.status?.id;
      if (statusId === 1 || statusId === 2) {
        setTimeout(() => {
          checkStatus(token);
        }, 2000);
        return;
      } else {
        setLoading(false);
        if (response.data.compile_output) {
          setUserOutput(atob(response.data.compile_output));
        } else if (response.data.stderr) {
          setUserOutput(atob(response.data.stderr));
        } else {
          setUserOutput(atob(response.data.stdout));
        }
      }
    } catch (err) {
      console.log(err);
    }
  };

  function clearOutput() {
    setUserOutput("");
  }

  return (
    <div>
      <Navbar
        userLanguage={userLanguage}
        setUserLanguage={setUserLanguage}
        userTheme={userTheme}
        setUserTheme={setUserTheme}
        fontSize={fontSize}
        setFontSize={setFontSize}
      />
      <div className="main">
        <div className="left-container">
          <div className="editor-container">
            <Editor
              options={options}
              width={`100%`}
              theme={userTheme}
              language={userLanguage}
              value={userCode}
              onChange={(value) => setUserCode(value)}
            />
          </div>
          <button className="run-btn" onClick={Compile}>
            Run
          </button>
        </div>
        <div className="right-container">
          {loading ? (
            <p>
              <Spinner />
            </p>
          ) : (
            <>
              {userOutput && (
                <div className="output-box">
                  <h4 className="text-dark">Output:</h4>
                  <pre>{userOutput}</pre>
                  <button onClick={clearOutput} className="clear-btn">
                    Clear
                  </button>
                </div>
              )}
            </>
          )}
        </div>
      </div>
    </div>
  );
}

export default Code;
