import React from 'react';
import CommentList from './components/CommentList'
import CommentCopyright from './components/CommentCopyright'
import AnyCommentComponent from "./components/AnyCommentComponent";
import 'iframe-resizer/js/iframeResizer.contentWindow'
import './css/comments.css'
import {ToastContainer} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

class App extends AnyCommentComponent {
    render() {
        return (
            <div id="anycomment-root" className={'anycomment anycomment-' + this.props.settings.options.theme}>
                <ToastContainer/>
                <CommentList/>
                <CommentCopyright/>
            </div>
        );
    }
}

export default App;