import React from 'react';
import AnyCommentComponent from './AnyCommentComponent'

/**
 * CommentAttachments is checking comment content for images, links, videos and
 * generates attachments from was was found.
 */
class CommentAttachments extends AnyCommentComponent {

    processAttachments() {
        const comment = this.props.comment;
        let attachments = [];

        const videosRe = /(^(https?:\/\/)?((www\.)?youtube\.com|youtu\.?be|rutube.ru)\/.+$)/gi;

        const imageRe = /((http(s?):)([/|.|\w|\s|-])*\.(?:jpg|jpeg|gif|png|svg))/gi;
        const videoMatches = [...new Set(comment.content.match(videosRe))];
        const imageMatches = [...new Set(comment.content.match(imageRe))];

        if (videoMatches.length > 0) {
            for (let i = 0; i < videoMatches.length; i++) {
                attachments.push(<li><a className="comment-attachment comment-attachment__link" href={videoMatches[i]}
                                        target="_blank"
                                        rel="noreferrer noopener"></a></li>);
            }
        }

        if (imageMatches.length > 0) {
            for (let k = 0; k < imageMatches.length; k++) {
                attachments.push(<li><a className="comment-attachment comment-attachment__image" href={imageMatches[k]}
                                        target="_blank"
                                        rel="noreferrer noopener"
                                        style={{backgroundImage: 'url(' + imageMatches[k] + ')'}}></a></li>);
            }
        }

        if (!attachments) {
            return null;
        }

        return <ul className="comment-attachments clearfix">{attachments}</ul>;
    }

    render() {
        return this.processAttachments();
    }
}

export default CommentAttachments;