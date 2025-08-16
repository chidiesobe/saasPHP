import { JSX } from 'react';
import { FaGithub, FaYahoo } from 'react-icons/fa';
import { FaXTwitter } from 'react-icons/fa6';
import { FcGoogle } from 'react-icons/fc';
import { TfiMicrosoftAlt } from 'react-icons/tfi';

interface SocialLoginProps {
    google_id: string;
    microsoft_id: string;
    yahoo_id: string;
    github_id: string;
    twitter_id: string;
}

export default function SocialLogins({ google_id, microsoft_id, yahoo_id, github_id, twitter_id }: SocialLoginProps): JSX.Element {
    return (
        <>
            <div className="flex w-full items-center justify-center">
                <div className="flex-1 border-t border-gray-400"></div>
                <span className="text-ms px-4 font-semibold text-gray-500">or login using</span>
                <div className="flex-1 border-t border-gray-400"></div>
            </div>
            <div className="flex items-center justify-center space-x-2 text-5xl">
                {google_id && (
                    <a href={route('google.login')}>
                        <FcGoogle className="rounded-lg border border-gray-400 p-2 hover:border-2" />
                    </a>
                )}

                {microsoft_id && (
                    <a href={route('microsoft.login')}>
                        <TfiMicrosoftAlt className="rounded-lg border border-gray-400 p-2 text-blue-400 hover:border-2" />
                    </a>
                )}

                {yahoo_id && (
                    <a href={route('yahoo.login')}>
                        <FaYahoo className="rounded-lg border border-gray-400 p-2 text-purple-500 hover:border-2" />
                    </a>
                )}

                {github_id && (
                    <a href={route('github.login')}>
                        <FaGithub className="rounded-lg border border-gray-400 p-2 hover:border-2" />
                    </a>
                )}

                {twitter_id && (
                    <a href={route('twitter.login')}>
                        <FaXTwitter className="rounded-lg border border-gray-400 p-2 hover:border-2" />
                    </a>
                )}
            </div>
        </>
    );
}
