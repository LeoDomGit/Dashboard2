import React, { useEffect, useState } from 'react'
import Layout from '../../components/Layout'
import Editor from "react-simple-code-editor";
import { highlight, languages } from "prismjs/components/prism-core";
import "prismjs/components/prism-clike";
import "prismjs/components/prism-javascript";
import "prismjs/themes/prism.css";
function Code() {
    const [code, setCode] = useState(
        "var message = 'Monaco Editor!' \nconsole.log(message);"
      );
      useEffect(()=>{
      },[])
  return (
    <Layout>
    <>
    <Editor
      value={code}
      padding={10}
      highlight={code => highlight(code, languages.js)}
      onValueChange={(code) => setCode(code)}
      style={{
        fontFamily: "monospace",
        fontSize: 17,
        border: "1px solid black"
      }}
    />
    </>
    </Layout>
  )
}

export default Code
